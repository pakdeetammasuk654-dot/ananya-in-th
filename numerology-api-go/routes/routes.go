package routes

import (
	"numerology-api-go/handlers"
	"github.com/gin-gonic/gin"
)

func SetupRoutes(r *gin.Engine) {
	memberGroup := r.Group("/member")
	{
		memberGroup.POST("/login", handlers.Login)
		// endpoints อื่นๆ ของ member จะถูกเพิ่มที่นี่
	}
}
