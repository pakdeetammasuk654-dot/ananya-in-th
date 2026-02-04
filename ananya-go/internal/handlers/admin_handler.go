package handlers

import (
	"ananya-go/internal/models"
	"net/http"

	"github.com/gin-gonic/gin"
	"gorm.io/gorm"
)

type AdminHandler struct {
	DB *gorm.DB
}

func NewAdminHandler(db *gorm.DB) *AdminHandler {
	return &AdminHandler{DB: db}
}

func (h *AdminHandler) TopicList(c *gin.Context) {
	var topics []models.Topic
	if err := h.DB.Order("topic_id DESC").Find(&topics).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}

	type TopicResponse struct {
		TopicID         int     `json:"topic_id"`
		HeadText        *string `json:"head_text"`
		TagPhone        string  `json:"tag_phone"`
		TagTabian       string  `json:"tag_tabian"`
		TagHome         string  `json:"tag_home"`
		TagNamesur      string  `json:"tag_namesur"`
		Paragraph1      *string `json:"paragraph1"`
		Paragraph2      *string `json:"paragraph2"`
		Paragraph3      *string `json:"paragraph3"`
		Photo1          *string `json:"photo1"`
		Photo2          *string `json:"photo2"`
		Photo3          *string `json:"photo3"`
		TopicDate       string  `json:"topic_date"`
		TopicDateUpdate string  `json:"topic_date_update"`
		PublicStatus    string  `json:"public_status"`
		AuthName        *string `json:"auth_name"`
	}

	var resp []TopicResponse
	for _, t := range topics {
		resp = append(resp, TopicResponse{
			TopicID:         t.TopicID,
			HeadText:        t.HeadText,
			TagPhone:        t.TagPhone,
			TagTabian:       t.TagTabian,
			TagHome:         t.TagHome,
			TagNamesur:      t.TagNamesur,
			Paragraph1:      t.Paragraph1,
			Paragraph2:      t.Paragraph2,
			Paragraph3:      t.Paragraph3,
			Photo1:          formatPhotoURL(t.Photo1),
			Photo2:          formatPhotoURL(t.Photo2),
			Photo3:          formatPhotoURL(t.Photo3),
			TopicDate:       t.TopicDate.Format("2006-01-02 15:04:05"),
			TopicDateUpdate: t.TopicDateUpdate.Format("2006-01-02 15:04:05"),
			PublicStatus:    t.PublicStatus,
			AuthName:        t.TopicAuth,
		})
	}

	c.JSON(http.StatusOK, gin.H{"topic_list": resp})
}

func formatPhotoURL(photo *string) *string {
	if photo == nil || *photo == "" {
		return nil
	}
	url := "https://www.ananya.in.th/public/photo/" + *photo + ".png"
	return &url
}

func (h *AdminHandler) ArticleList(c *gin.Context) {
	var articles []models.Article
	if err := h.DB.Order("art_id DESC").Find(&articles).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusOK, articles)
}
