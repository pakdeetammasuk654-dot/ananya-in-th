package database

import (
	"database/sql"
	"fmt"
	_ "github.com/lib/pq"
)

// ConnectDB connects to the PostgreSQL database and returns a database object.
func ConnectDB(host, user, password, dbname string) (*sql.DB, error) {
	psqlInfo := fmt.Sprintf("host=%s user=%s password=%s dbname=%s sslmode=disable",
		host, user, password, dbname)

	db, err := sql.Open("postgres", psqlInfo)
	if err != nil {
		return nil, err
	}

	err = db.Ping()
	if err != nil {
		return nil, err
	}

	fmt.Println("Successfully connected to the database!")
	return db, nil
}
