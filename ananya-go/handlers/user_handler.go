package handlers

import (
	"ananya-go/db"
	"ananya-go/models"
	"database/sql"
	"encoding/json"
	"golang.org/x/crypto/bcrypt"
	"net/http"
)

func Register(w http.ResponseWriter, r *http.Request) {
	var user models.User
	decoder := json.NewDecoder(r.Body)
	if err := decoder.Decode(&user); err != nil {
		http.Error(w, "Invalid request body", http.StatusBadRequest)
		return
	}
	defer r.Body.Close()

	// Hash the password for security
	hashedPassword, err := bcrypt.GenerateFromPassword([]byte(*user.Password), bcrypt.DefaultCost)
	if err != nil {
		http.Error(w, "Error creating user", http.StatusInternalServerError)
		return
	}
	hashedPasswordStr := string(hashedPassword)
	user.Password = &hashedPasswordStr

	sqlStatement := `
		INSERT INTO membertb (username, password, realname, surname, vipcode, status)
		VALUES ($1, $2, $3, $4, 'normal', 'active')
		RETURNING memberid`

	var memberID int
	err = db.DB.QueryRow(sqlStatement, user.Username, user.Password, user.RealName, user.Surname).Scan(&memberID)
	if err != nil {
		// This could be a unique constraint violation (username exists)
		// Or some other database error.
		http.Error(w, "Could not create user", http.StatusInternalServerError)
		return
	}

	user.MemberID = memberID
	// Don't send the password back
	user.Password = nil

	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(http.StatusCreated)
	json.NewEncoder(w).Encode(user)
}

func Login(w http.ResponseWriter, r *http.Request) {
	var creds struct {
		Username string `json:"username"`
		Password string `json:"password"`
	}
	decoder := json.NewDecoder(r.Body)
	if err := decoder.Decode(&creds); err != nil {
		http.Error(w, "Invalid request body", http.StatusBadRequest)
		return
	}
	defer r.Body.Close()

	var user models.User
	var hashedPassword string

	sqlStatement := `SELECT memberid, username, password, realname, surname, vipcode, status FROM membertb WHERE username=$1`
	err := db.DB.QueryRow(sqlStatement, creds.Username).Scan(
		&user.MemberID, &user.Username, &hashedPassword, &user.RealName, &user.Surname, &user.VIPCode, &user.Status,
	)

	if err != nil {
		if err == sql.ErrNoRows {
			http.Error(w, "User not found", http.StatusNotFound)
		} else {
			http.Error(w, "Database error", http.StatusInternalServerError)
		}
		return
	}

	// Compare the stored hashed password with the provided password
	if err = bcrypt.CompareHashAndPassword([]byte(hashedPassword), []byte(creds.Password)); err != nil {
		// If the passwords do not match, send an unauthorized status
		http.Error(w, "Invalid login credentials", http.StatusUnauthorized)
		return
	}

	// Don't send the password back
	user.Password = nil

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(user)
}
