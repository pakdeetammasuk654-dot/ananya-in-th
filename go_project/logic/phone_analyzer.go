package logic

import (
	"database/sql"
	"fmt"
	"phone-analyzer/models"
	"sort"
	"strconv"
	"strings"
)

// AnalyzePhoneNumber performs the entire analysis of a given phone number.
func AnalyzePhoneNumber(phoneNumber string, db *sql.DB) (models.AnalysisResult, error) {
	var result models.AnalysisResult

	// 1. Set Pairs Number
	pairsA, pairsB := setPairsNumber(phoneNumber)

	// 2. Set Pair Sum
	pairSum := setPairSum(phoneNumber)

	// 3. Set Pairs Unique
	pairsUnique := setPairsUnique(pairsA, pairsB, pairSum)

	// 4. Set Pair Miracle
	pairMiracle, err := setPairMiracle(pairsUnique, db)
	if err != nil {
		return result, fmt.Errorf("error setting pair miracle: %w", err)
	}

	// 5. Set Obj Pairs
	objPairSum := setObjPairSum(pairMiracle, pairSum)
	objPairsA := setObjPairsA(pairMiracle, pairsA)
	objPairsB := setObjPairsB(pairMiracle, pairsB)

	result.PairsA = objPairsA
	result.PairsB = objPairsB
	result.PairSum = objPairSum

	// 6. Set Scores
	scoreLastPairA := setScoreLastPairA(objPairsA)
	scoreContinueA := setScoreContinueA(objPairsA)
	scoreContinueB := setScoreContinueB(objPairsB)

	result.ScoreLastPairA = map[string]int{"scoreLastPairA": scoreLastPairA}
	result.ScoreContinueA = scoreContinueA
	result.ScoreContinueB = scoreContinueB

	// 7. Set Score Total
	scoreTotal := setScoreTotal(scoreContinueA, scoreContinueB, scoreLastPairA, objPairsA, objPairsB, objPairSum)
	result.ScoreTotal = scoreTotal

	// 8. Set Percent Position
	percentPosition := setPercentPosition(objPairsA, objPairsB, objPairSum)
	result.PercentPosition = percentPosition

	// 9. Set Miracle Sort Percent
	miracleSortPercent := setMiracleSortPercent(pairMiracle, objPairsA, objPairsB, objPairSum)
	result.PairMiracle = miracleSortPercent

	// 10. Set Summary Miracle Score
	summaryMiracleScore := setSummaryMiracleScore(scoreTotal)
	result.SummaryMiracleScore = summaryMiracleScore

	return result, nil
}

func setPairsNumber(phoneNumber string) ([]string, []string) {
	pairsA := []string{}
	for i := 0; i < len(phoneNumber); i += 2 {
		pairsA = append(pairsA, phoneNumber[i:i+2])
	}

	pairsB := []string{
		phoneNumber[1:3],
		phoneNumber[3:5],
		phoneNumber[5:7],
		phoneNumber[7:9],
	}
	return pairsA, pairsB
}

func setPairSum(phoneNumber string) string {
	sum := 0
	for _, char := range phoneNumber {
		digit, _ := strconv.Atoi(string(char))
		sum += digit
	}
	return strconv.Itoa(sum)
}

func setPairsUnique(pairsA, pairsB []string, pairSum string) []string {
	uniqueMap := make(map[string]bool)
	for _, p := range pairsA {
		uniqueMap[p] = true
	}
	for _, p := range pairsB {
		uniqueMap[p] = true
	}
	uniqueMap[pairSum] = true

	uniqueSlice := []string{}
	for p := range uniqueMap {
		uniqueSlice = append(uniqueSlice, p)
	}
	return uniqueSlice
}

func setPairMiracle(pairUnique []string, db *sql.DB) ([]models.Number, error) {
	var miracles []models.Number

	// Create a placeholder string for the IN clause
    placeholders := make([]string, len(pairUnique))
    args := make([]interface{}, len(pairUnique))
    for i, p := range pairUnique {
        placeholders[i] = "$" + strconv.Itoa(i+1)
        args[i] = p
    }

    query := fmt.Sprintf("SELECT pairnumberid, pairnumber, pairtype, pairpoint, miracledesc, miracledetail FROM numbers WHERE pairnumber IN (%s) ORDER BY pairnumberid ASC", strings.Join(placeholders, ","))

	rows, err := db.Query(query, args...)

	if err != nil {
		return nil, err
	}
	defer rows.Close()

	for rows.Next() {
		var n models.Number
		if err := rows.Scan(&n.PairNumberID, &n.PairNumber, &n.PairType, &n.PairPoint, &n.MiracleDesc, &n.MiracleDetail); err != nil {
			return nil, err
		}
		miracles = append(miracles, n)
	}
	return miracles, nil
}

func setObjPairSum(pairMiracle []models.Number, pairSum string) models.PairDetail {
	for _, m := range pairMiracle {
		if m.PairNumber == pairSum {
			return models.PairDetail{
				PairNumber: m.PairNumber,
				PairType:   m.PairType,
				PairPoint:  m.PairPoint,
				Percentile: 20,
			}
		}
	}
	return models.PairDetail{}
}

func setObjPairsA(pairMiracle []models.Number, pairsA []string) []models.PairDetail {
	var objPairsA []models.PairDetail
	percentiles := []int{5, 5, 10, 15, 20}
	for i, pA := range pairsA {
		for _, m := range pairMiracle {
			if pA == m.PairNumber {
				objPairsA = append(objPairsA, models.PairDetail{
					PairNumber: m.PairNumber,
					PairType:   m.PairType,
					PairPoint:  m.PairPoint,
					Percentile: percentiles[i],
				})
				break
			}
		}
	}
	return objPairsA
}

func setObjPairsB(pairMiracle []models.Number, pairsB []string) []models.PairDetail {
	var objPairsB []models.PairDetail
	percentiles := []int{3, 5, 5, 12}
	for i, pB := range pairsB {
		for _, m := range pairMiracle {
			if pB == m.PairNumber {
				objPairsB = append(objPairsB, models.PairDetail{
					PairNumber: m.PairNumber,
					PairType:   m.PairType,
					PairPoint:  m.PairPoint,
					Percentile: percentiles[i],
				})
				break
			}
		}
	}
	return objPairsB
}

func setScoreLastPairA(objPairsA []models.PairDetail) int {
	if len(objPairsA) > 0 {
		return objPairsA[len(objPairsA)-1].PairPoint
	}
	return 0
}

func setScoreContinueA(objPairsA []models.PairDetail) map[string]int {
	n := 0
	score := 0
	if len(objPairsA) > 0 {
		lastPairType := objPairsA[len(objPairsA)-1].PairType[0]
		for i := len(objPairsA) - 1; i >= 0; i-- {
			if objPairsA[i].PairType[0] == lastPairType {
				score += objPairsA[i].PairPoint
				n++
			} else {
				break
			}
		}
	}
	return map[string]int{"continue": n, "score": score}
}

func setScoreContinueB(objPairsB []models.PairDetail) map[string]int {
	n := 0
	score := 0
	if len(objPairsB) > 0 {
		lastPairType := objPairsB[len(objPairsB)-1].PairType[0]
		for i := len(objPairsB) - 1; i >= 0; i-- {
			if objPairsB[i].PairType[0] == lastPairType {
				score += objPairsB[i].PairPoint
				n++
			} else {
				break
			}
		}
	}
	return map[string]int{"continue": n, "score": score}
}

func setScoreTotal(scoreContinueA, scoreContinueB map[string]int, scoreLastPairA int, objPairsA, objPairsB []models.PairDetail, objPairSum models.PairDetail) map[string]int {
	totalScoreD := 0
	totalScoreR := 0

	if s, ok := scoreContinueA["score"]; ok && s >= 0 {
		totalScoreD += s
	} else if ok {
		totalScoreR += s
	}

	if s, ok := scoreContinueB["score"]; ok && s >= 0 {
		totalScoreD += s
	} else if ok {
		totalScoreR += s
	}

	if scoreLastPairA >= 0 {
		totalScoreD += scoreLastPairA
	} else {
		totalScoreR += scoreLastPairA
	}

	for _, p := range objPairsA {
		if p.PairPoint >= 0 {
			totalScoreD += p.PairPoint
		} else {
			totalScoreR += p.PairPoint
		}
	}

	for _, p := range objPairsB {
		if p.PairPoint >= 0 {
			totalScoreD += p.PairPoint
		} else {
			totalScoreR += p.PairPoint
		}
	}

	if objPairSum.PairPoint >= 0 {
		totalScoreD += objPairSum.PairPoint
	} else {
		totalScoreR += objPairSum.PairPoint
	}

	return map[string]int{"totalScoreD": totalScoreD, "totalScoreR": totalScoreR}
}

func setPercentPosition(objPairsA, objPairsB []models.PairDetail, objPairSum models.PairDetail) map[string]int {
	percentD := 0
	percentR := 0

	for _, p := range objPairsA {
		if p.PairType[0] == 'D' {
			percentD += p.Percentile
		} else {
			percentR += p.Percentile
		}
	}

	for _, p := range objPairsB {
		if p.PairType[0] == 'D' {
			percentD += p.Percentile
		} else {
			percentR += p.Percentile
		}
	}

	if objPairSum.PairType[0] == 'D' {
		percentD += objPairSum.Percentile
	} else {
		percentR += objPairSum.Percentile
	}

	return map[string]int{"percentD": percentD, "percentR": percentR}
}

func setMiracleSortPercent(pairMiracle []models.Number, objPairsA, objPairsB []models.PairDetail, objPairSum models.PairDetail) []models.MiracleDetail {

	miracleMap := make(map[string]models.MiracleDetail)

	// Initialize map with all miracles
	for _, m := range pairMiracle {
		miracleMap[m.PairNumber] = models.MiracleDetail{
			PairNumber:    m.PairNumber,
			PairType:      m.PairType,
			PairPoint:     m.PairPoint,
			Percentile:    0, // Start with 0
			MiracleDesc:   m.MiracleDesc,
			MiracleDetail: m.MiracleDetail,
		}
	}

	// Accumulate percentiles
	for _, p := range objPairsA {
		if detail, ok := miracleMap[p.PairNumber]; ok {
			detail.Percentile += p.Percentile
			miracleMap[p.PairNumber] = detail
		}
	}
	for _, p := range objPairsB {
		if detail, ok := miracleMap[p.PairNumber]; ok {
			detail.Percentile += p.Percentile
			miracleMap[p.PairNumber] = detail
		}
	}
	if detail, ok := miracleMap[objPairSum.PairNumber]; ok {
		detail.Percentile += objPairSum.Percentile
		miracleMap[objPairSum.PairNumber] = detail
	}

	// Convert map to slice and filter out those with 0 percentile
	var result []models.MiracleDetail
	for _, detail := range miracleMap {
		if detail.Percentile > 0 {
			result = append(result, detail)
		}
	}

	// Sort by percentile descending
	sort.Slice(result, func(i, j int) bool {
		return result[i].Percentile > result[j].Percentile
	})

	return result
}

func setSummaryMiracleScore(scoreTotal map[string]int) []map[string]interface{} {
	var summary []map[string]interface{}
	totalScoreD := scoreTotal["totalScoreD"]
	totalScoreR := scoreTotal["totalScoreR"]

	// Grade for "D" scores (Good scores)
	if totalScoreD >= 924 {
			summary = append(summary, map[string]interface{}{"grade": "A+", "miracle": "เบอร์นี้ดีเยี่ยม VIP ผลร้ายไม่มีอิทธิผล มีเลขดีที่มีอิทธิผลต่อชีวิตสูง และมีความแม่นยำสูง ใช้แล้วเจริญรุ่งเรื่องดีนักแล", "scoreTotal": totalScoreD})
	} else if totalScoreD >= 700 {
			summary = append(summary, map[string]interface{}{"grade": "B", "miracle": "เบอร์นี้ดีเยี่ยม ผลร้ายไม่มีอิทธิผล มีเลขดีที่มีอิทธิผลต่อชีวิตสูง ใช้แล้วชีวิตรุ่งเรืองดีมาก", "scoreTotal": totalScoreD})
	} else if totalScoreD >= 654 {
			summary = append(summary, map[string]interface{}{"grade": "C", "miracle": "เบอร์นี้ดี ผลร้ายมีอิทธิผลน้อย มีเลขดีที่มีอิทธิผลต่อชีวิตสูง", "scoreTotal": totalScoreD})
	} else if totalScoreD >= 540 {
			summary = append(summary, map[string]interface{}{"grade": "D", "miracle": "เบอร์นี้มีผลดีต่อชีวิตระดับปานกลาง มีเลขดีที่ส่งผลระดับปานกลาง", "scoreTotal": totalScoreD})
	} else {
			summary = append(summary, map[string]interface{}{"grade": "F", "miracle": "อันตราย ควรเปลี่ยนเบอร์", "scoreTotal": totalScoreD})
	}

	// Grade for "R" scores (Bad scores)
	if totalScoreR <= -170 {
			summary = append(summary, map[string]interface{}{"grade": "xA+", "miracle": "อันตราย ควรเปลี่ยนเบอร์ทันที", "scoreTotalR": totalScoreR})
	} else if totalScoreR <= -130 {
			summary = append(summary, map[string]interface{}{"grade": "xB", "miracle": "อันตราย ระวังให้มากต้องรอบคอบหมั่นทำบุญ", "scoreTotalR": totalScoreR})
	} else if totalScoreR <= -100 {
			summary = append(summary, map[string]interface{}{"grade": "xC", "miracle": "อย่าประมาท ทำบุญเสริมบ้างจะดีไม่ตก", "scoreTotalR": totalScoreR})
	} else if totalScoreR <= -40 {
			summary = append(summary, map[string]interface{}{"grade": "xD", "miracle": "สมดุลชีวิตทำบุญเสริมราบรื่นตลอด", "scoreTotalR": totalScoreR})
	} else {
			summary = append(summary, map[string]interface{}{"grade": "xF", "miracle": "ไม่มีอิทธิพล ทำบุญเสริมราบรื่นตลอด", "scoreTotalR": totalScoreR})
	}

	return summary
}
