package handlers

import (
	"encoding/json"
	"go_numerology_api/internal/database"
	"golang.org/x/crypto/bcrypt"
	"net/http"
)

// User struct for registration request
type User struct {
	Username string `json:"username"`
	Password string `json:"password"`
	Realname string `json:"realname"`
	Surname  string `json:"surname"`
}

// Credentials struct for login request
type Credentials struct {
	Username string `json:"username"`
	Password string `json:"password"`
}

// RespondWithError is a helper function to send a JSON error message
func RespondWithError(w http.ResponseWriter, code int, message string) {
	RespondWithJSON(w, code, map[string]string{"error": message})
}

// RespondWithJSON is a helper function to send a JSON response
func RespondWithJSON(w http.ResponseWriter, code int, payload interface{}) {
	response, _ := json.Marshal(payload)
	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(code)
	w.Write(response)
}

// RegisterUser handles new user registration
func RegisterUser(w http.ResponseWriter, r *http.Request) {
	if r.Method != "POST" {
		RespondWithError(w, http.StatusMethodNotAllowed, "Only POST method is allowed")
		return
	}

	var user User
	if err := json.NewDecoder(r.Body).Decode(&user); err != nil {
		RespondWithError(w, http.StatusBadRequest, "Invalid request payload")
		return
	}
	defer r.Body.Close()

	if user.Username == "" || user.Password == "" {
		RespondWithError(w, http.StatusBadRequest, "Username and password are required")
		return
	}

	// Hash the password
	hashedPassword, err := bcrypt.GenerateFromPassword([]byte(user.Password), bcrypt.DefaultCost)
	if err != nil {
		RespondWithError(w, http.StatusInternalServerError, "Failed to hash password")
		return
	}

	// Insert user into the database
	sqlStatement := `
		INSERT INTO membertb (username, password, realname, surname, vipcode, status)
		VALUES ($1, $2, $3, $4, 'normal', 'active')
		RETURNING memberid`

	var id int
	err = database.DB.QueryRow(sqlStatement, user.Username, string(hashedPassword), user.Realname, user.Surname).Scan(&id)
	if err != nil {
		// This could be a unique constraint violation (username already exists)
		RespondWithError(w, http.StatusInternalServerError, "Failed to create user")
		return
	}

	RespondWithJSON(w, http.StatusCreated, map[string]interface{}{"message": "User created successfully", "memberid": id})
}

// LoginUser handles user authentication
func LoginUser(w http.ResponseWriter, r *http.Request) {
	if r.Method != "POST" {
		RespondWithError(w, http.StatusMethodNotAllowed, "Only POST method is allowed")
		return
	}

	var creds Credentials
	if err := json.NewDecoder(r.Body).Decode(&creds); err != nil {
		RespondWithError(w, http.StatusBadRequest, "Invalid request payload")
		return
	}
	defer r.Body.Close()

	var storedPassword string
	var memberid int

	// Query the database for the user
	sqlStatement := `SELECT memberid, password FROM membertb WHERE username=$1`
	err := database.DB.QueryRow(sqlStatement, creds.Username).Scan(&memberid, &storedPassword)
	if err != nil {
		// If no user is found, return an unauthorized error
		RespondWithError(w, http.StatusUnauthorized, "Invalid username or password")
		return
	}

	// Compare the stored hashed password with the login password
	if err := bcrypt.CompareHashAndPassword([]byte(storedPassword), []byte(creds.Password)); err != nil {
		// If the passwords do not match, return an unauthorized error
		RespondWithError(w, http.StatusUnauthorized, "Invalid username or password")
		return
	}

	// For now, just return a success message.
	// In a real application, you would generate and return a JWT here.
	RespondWithJSON(w, http.StatusOK, map[string]interface{}{"message": "Login successful", "memberid": memberid})
}
