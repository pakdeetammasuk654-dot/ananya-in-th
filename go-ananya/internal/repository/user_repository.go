package repository

import (
	"go-ananya/internal/models"
	"gorm.io/gorm"
)

type UserRepository struct {
	db *gorm.DB
}

func NewUserRepository(db *gorm.DB) *UserRepository {
	return &UserRepository{db: db}
}

func (r *UserRepository) FindByUsername(username string) (*models.Member, error) {
	var user models.Member
	err := r.db.Where("username = ?", username).First(&user).Error
	if err != nil {
		return nil, err
	}
	return &user, nil
}

func (r *UserRepository) Create(user *models.Member) error {
	return r.db.Create(user).Error
}

func (r *UserRepository) Update(user *models.Member) error {
	return r.db.Save(user).Error
}
