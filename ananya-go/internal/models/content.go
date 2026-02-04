package models

import (
	"time"
)

type Article struct {
	ArtID       int        `gorm:"primaryKey;column:art_id"`
	Slug        string     `gorm:"column:slug;unique"`
	Title       string     `gorm:"column:title"`
	Excerpt     *string    `gorm:"column:excerpt"`
	Category    *string    `gorm:"column:category"`
	ImageURL    *string    `gorm:"column:image_url"`
	PublishedAt *time.Time `gorm:"column:published_at"`
	IsPublished int        `gorm:"column:is_published;default:0"`
	Content     *string    `gorm:"column:content"`
	TitleShort  *string    `gorm:"column:title_short"`
	PinOrder    int        `gorm:"column:pin_order;default:0"`
	CreatedAt   time.Time  `gorm:"column:created_at;autoCreateTime"`
	UpdatedAt   time.Time  `gorm:"column:updated_at;autoUpdateTime"`
}

func (Article) TableName() string {
	return "articles"
}

type News struct {
	NewsID        int     `gorm:"primaryKey;column:newsid"`
	NewsPicHeader *string `gorm:"column:news_pic_header"`
	NewsAuth      *string `gorm:"column:news_auth"`
	NewsType      *string `gorm:"column:news_type"`
	NewsHeadline  *string `gorm:"column:news_headline"`
	NewsPicInside *string `gorm:"column:news_pic_inside"`
	NewsDesc      *string `gorm:"column:news_desc"`
	NewsDetail    *string `gorm:"column:news_detail"`
}

func (News) TableName() string {
	return "news"
}

type Topic struct {
	TopicID         int       `gorm:"primaryKey;column:topic_id"`
	HeadText        *string   `gorm:"column:head_text"`
	DescText        *string   `gorm:"column:desc_text"`
	TagPhone        string    `gorm:"column:tag_phone;default:false"`
	TagTabian       string    `gorm:"column:tag_tabian;default:false"`
	TagHome         string    `gorm:"column:tag_home;default:false"`
	TagNamesur      string    `gorm:"column:tag_namesur;default:false"`
	Paragraph1      *string   `gorm:"column:paragraph1"`
	Paragraph2      *string   `gorm:"column:paragraph2"`
	Paragraph3      *string   `gorm:"column:paragraph3"`
	Photo1          *string   `gorm:"column:photo1"`
	Photo2          *string   `gorm:"column:photo2"`
	Photo3          *string   `gorm:"column:photo3"`
	TopicDate       time.Time `gorm:"column:topic_date;default:CURRENT_TIMESTAMP"`
	TopicDateUpdate time.Time `gorm:"column:topic_date_update;default:CURRENT_TIMESTAMP"`
	TopicAuth       *string   `gorm:"column:topic_auth"`
	PublicStatus    string    `gorm:"column:public_status;default:false"`
}

func (Topic) TableName() string {
	return "topictb"
}

type MiracleDo struct {
	MiraID   int    `gorm:"primaryKey;column:miraid"`
	Activity string `gorm:"column:activity"`
	DayX     string `gorm:"column:dayx"`
	DayY     string `gorm:"column:dayy"`
	Action   int    `gorm:"column:action;default:1"`
	MiraIDRef string `gorm:"column:mira_id"`
}

func (MiracleDo) TableName() string {
	return "miracledo"
}

type MiracleDoDesc struct {
	MiraID   int    `gorm:"primaryKey;column:mira_id"`
	Activity string `gorm:"column:activity"`
	MiraDay  string `gorm:"column:mira_day"`
	MiraDesc string `gorm:"column:mira_desc"`
}

func (MiracleDoDesc) TableName() string {
	return "miracledo_desc"
}
