package database

import (
	"database/sql"
	"go-numerology-api/internal/models"

	"golang.org/x/crypto/bcrypt"
)

// CreateUser inserts a new user into the database
func CreateUser(db *sql.DB, user *models.RegistrationRequest) error {
	hashedPassword, err := bcrypt.GenerateFromPassword([]byte(user.Password), bcrypt.DefaultCost)
	if err != nil {
		return err
	}

	sqlStatement := `
		INSERT INTO membertb (username, password, realname, surname, status, vipcode)
		VALUES ($1, $2, $3, $4, 'active', 'normal')
		RETURNING memberid`

	err = db.QueryRow(sqlStatement, user.Username, string(hashedPassword), user.Realname, user.Surname).Scan(&sql.NullInt64{}) // We don't need the returned id for now

	return err
}

// GetUserByUsername retrieves a user by their username
func GetUserByUsername(db *sql.DB, username string) (*models.User, error) {
	user := &models.User{}
	sqlStatement := `SELECT memberid, username, password, realname, surname, vipcode, status FROM membertb WHERE username=$1`

	err := db.QueryRow(sqlStatement, username).Scan(&user.MemberID, &user.Username, &user.Password, &user.Realname, &user.Surname, &user.VipCode, &user.Status)
	if err != nil {
		return nil, err
	}

	return user, nil
}

// CheckPassword compares a plaintext password with a hashed password
func CheckPassword(hashedPassword, password string) bool {
	err := bcrypt.CompareHashAndPassword([]byte(hashedPassword), []byte(password))
	return err == nil
}
