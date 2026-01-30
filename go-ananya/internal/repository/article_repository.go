package repository

import (
	"go-ananya/internal/models"
	"gorm.io/gorm"
)

type ArticleRepository struct {
	db *gorm.DB
}

func NewArticleRepository(db *gorm.DB) *ArticleRepository {
	return &ArticleRepository{db: db}
}

func (r *ArticleRepository) ListPublished(limit int) ([]models.Article, error) {
	var articles []models.Article
	err := r.db.Where("is_published = ?", true).Order("pin_order DESC, published_at DESC").Limit(limit).Find(&articles).Error
	return articles, err
}

func (r *ArticleRepository) FindByID(id int) (*models.Article, error) {
	var article models.Article
	err := r.db.First(&article, id).Error
	return &article, err
}

func (r *ArticleRepository) FindBySlug(slug string) (*models.Article, error) {
	var article models.Article
	err := r.db.Where("slug = ? AND is_published = ?", slug, true).First(&article).Error
	return &article, err
}
