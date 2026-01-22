package repository

import (
	"database/sql"
	"go-numerology-api/models"
)

// MemberRepositoryInterface defines the methods for member repository
type MemberRepositoryInterface interface {
	CreateMember(member *models.Member) (int, error)
}

// MemberRepository handles database operations for members
type MemberRepository struct {
	DB *sql.DB
}

// NewMemberRepository creates a new MemberRepository
func NewMemberRepository(db *sql.DB) *MemberRepository {
	return &MemberRepository{DB: db}
}

// CreateMember inserts a new member into the database
func (r *MemberRepository) CreateMember(member *models.Member) (int, error) {
	query := `
		INSERT INTO membertb (username, password, realname, surname, birthday)
		VALUES ($1, $2, $3, $4, $5)
		RETURNING memberid
	`
	var memberID int
	err := r.DB.QueryRow(
		query,
		member.Username,
		member.Password,
		member.Realname,
		member.Surname,
		member.Birthday,
	).Scan(&memberID)

	if err != nil {
		return 0, err
	}

	return memberID, nil
}
