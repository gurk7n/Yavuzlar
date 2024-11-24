package main

import (
	"encoding/json"
	"fmt"
	"os"
	"strings"
	"unicode/utf8"

	"github.com/gocolly/colly"
)

type Haber struct {
	Baslik  string `json:"baslik"`
	Tarih   string `json:"tarih"`
	Yazi    string `json:"yazi"`
}

type YoutubeKanali struct {
	Ad          string `json:"ad"`
	Kategori    string `json:"kategori"`
	AboneSayisi string `json:"aboneSayisi"`
}

type SehirHavaDurumu struct {
	Sehir         string `json:"sehir"`
	MaxSicaklik   string `json:"maxSicaklik"`
	MinSicaklik   string `json:"minSicaklik"`
	Durum         string `json:"durum"`
}

func main() {
	fmt.Println("1 - TheHackerNews Haberleri")
	fmt.Println("2 - Günlük Hava Durumu")
	fmt.Println("3 - Youtube Abone Sıralaması")
	fmt.Println("4 - Çıkış")
	fmt.Print("Seçim: ")
	var menu string
	fmt.Scan(&menu)
	switch menu {
	case "1":
		hackerNews()
	case "2":
		havaDurumu()
	case "3":
		youtubeAbone()
	case "4":
		os.Exit(0)
	}
}

func hackerNews() {
	c := colly.NewCollector()
	var haberler []Haber

	c.OnHTML("div.body-post", func(h *colly.HTMLElement) {
		fullYazi := h.ChildText("div.home-desc")
		ilkCumle := fullYazi
		tarih := h.ChildText("span.h-datetime")

		_, size := utf8.DecodeRuneInString(tarih)
		tarih = tarih[size:]

		tarih = strings.TrimSpace(tarih)

		haberler = append(haberler, Haber{
			Baslik: h.ChildText("h2.home-title"),
			Tarih:  tarih,
			Yazi:   ilkCumle,
		})
	})

	c.OnScraped(func(r *colly.Response) {
		file, _ := os.Create("data.json")
		defer file.Close()

		encoder := json.NewEncoder(file)
		encoder.SetIndent("", "  ")
		encoder.Encode(haberler)

		for _, haber := range haberler {
			fmt.Println("--------")
			fmt.Printf("Başlık: %s\n", haber.Baslik)
			fmt.Printf("Tarih: %s\n", haber.Tarih)
			fmt.Printf("Yazı: %s\n", haber.Yazi)
		}
		fmt.Println("--------")
	})

	c.Visit("https://www.thehackernews.com")
}

func youtubeAbone() {
	c := colly.NewCollector()
	var youtubeKanallari []YoutubeKanali

	c.OnHTML("div.table-line.clearfix", func(h *colly.HTMLElement) {
		ad := h.ChildText("span.title.pull-left.ellipsis")
		kategori := h.ChildText("a.category")

		aboneSayisi := h.ChildText("span.rank-cell.pull-left.rank-subs span.number")
		if aboneSayisi != "" {
			youtubeKanallari = append(youtubeKanallari, YoutubeKanali{
				Ad:          ad,
				Kategori:    kategori,
				AboneSayisi: aboneSayisi,
			})
		}
	})

	c.OnScraped(func(r *colly.Response) {
		file, _ := os.Create("data.json")
		defer file.Close()

		encoder := json.NewEncoder(file)
		encoder.SetIndent("", "  ")
		encoder.Encode(youtubeKanallari)

		for _, kanal := range youtubeKanallari {
			fmt.Println("--------")
			fmt.Printf("Ad: %s\n", kanal.Ad)
			fmt.Printf("Kategori: %s\n", kanal.Kategori)
			fmt.Printf("Abone Sayısı: %s\n", kanal.AboneSayisi)
		}
		fmt.Println("--------")
	})

	c.Visit("https://www.noxinfluencer.com/youtube-channel-rank/top-100-all-all-youtuber-sorted-by-subs-weekly")
}

func havaDurumu() {
	c := colly.NewCollector()
	var sehirHavaDurumlari []SehirHavaDurumu

	c.OnHTML("div.weather-main__card-body", func(h *colly.HTMLElement) {
		sehir := h.ChildText("strong.weather-main__city-name")
		maxSicaklik := h.ChildText("strong.weather-main__temp-value--max")
		minSicaklik := h.ChildText("span.weather-main__temp-value--min")
		durum := h.ChildText("div.weather-main__condition strong")

		sehirHavaDurumlari = append(sehirHavaDurumlari, SehirHavaDurumu{
			Sehir:       sehir,
			MaxSicaklik: maxSicaklik,
			MinSicaklik: minSicaklik,
			Durum:       durum,
		})
	})

	c.OnScraped(func(r *colly.Response) {
		file, _ := os.Create("data.json")
		defer file.Close()

		encoder := json.NewEncoder(file)
		encoder.SetIndent("", "  ")
		encoder.Encode(sehirHavaDurumlari)

		for _, durum := range sehirHavaDurumlari {
			fmt.Println("--------")
			fmt.Printf("Şehir: %s\n", durum.Sehir)
			fmt.Printf("Max Sıcaklık: %s\n", durum.MaxSicaklik)
			fmt.Printf("Min Sıcaklık: %s\n", durum.MinSicaklik)
			fmt.Printf("Durum: %s\n", durum.Durum)
		}
		fmt.Println("--------")
	})

	c.Visit("https://www.milliyet.com.tr/hava-durumu/")
}
