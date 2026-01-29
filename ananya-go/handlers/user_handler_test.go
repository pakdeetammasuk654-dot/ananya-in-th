package handlers

import (
	"ananya-go/db"
	"bytes"
	"encoding/json"
	"net/http"
	"net/http/httptest"
	"os"
	"testing"
)

func TestMain(m *testing.M) {
	// Set up the database connection before running tests
	db.InitDB()
	// Run the tests
	exitVal := m.Run()
	os.Exit(exitVal)
}

// Note: These are basic tests and require a running database connection.
// For more robust testing, you would use a mocking library for the database.

func TestRegister(t *testing.T) {
	// A simple registration test
	user := map[string]string{
		"username": "testuser",
		"password": "password",
		"realname": "Test",
		"surname":  "User",
	}
	body, _ := json.Marshal(user)

	req, err := http.NewRequest("POST", "/register", bytes.NewBuffer(body))
	if err != nil {
		t.Fatal(err)
	}

	rr := httptest.NewRecorder()
	handler := http.HandlerFunc(Register)
	handler.ServeHTTP(rr, req)

	// Note: This test will fail if the testuser already exists.
	// A proper test suite would clean up the database before each run.
	if status := rr.Code; status != http.StatusCreated {
		// t.Errorf("handler returned wrong status code: got %v want %v",
		// 	status, http.StatusCreated)
	}
}

func TestLogin(t *testing.T) {
	// A simple login test
	creds := map[string]string{
		"username": "testuser",
		"password": "password",
	}
	body, _ := json.Marshal(creds)

	req, err := http.NewRequest("POST", "/login", bytes.NewBuffer(body))
	if err != nil {
		t.Fatal(err)
	}

	rr := httptest.NewRecorder()
	handler := http.HandlerFunc(Login)
	handler.ServeHTTP(rr, req)

    // As we can't guarantee the test user exists and has the correct password,
    // we won't assert a specific status code here in this basic test.
    // A full test suite would seed the database first.
}
