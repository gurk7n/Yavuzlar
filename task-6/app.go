package main

import (
	"bufio"
	"fmt"
	"os"
	"strings"
	"time"
)

type Kullanici struct {
	kullaniciAdi string
	sifre        string
	rol          string
}

var kullanicilar = make(map[string]Kullanici)

func kullanicilariYukle() {
	dosya, err := os.Open("kullanicilar.txt")
	if err != nil {
		fmt.Println("Kullanıcı dosyası açılamadı:", err)
		return
	}
	defer dosya.Close()

	scanner := bufio.NewScanner(dosya)
	for scanner.Scan() {
		satir := scanner.Text()
		parcalar := strings.Split(satir, ",")
		if len(parcalar) == 3 {
			kullaniciAdi := parcalar[0]
			sifre := parcalar[1]
			rol := parcalar[2]
			kullanicilar[kullaniciAdi] = Kullanici{kullaniciAdi, sifre, rol}
		}
	}
}

func girisYap(scanner *bufio.Scanner) {
	fmt.Println("Kullanıcı adınızı girin:")
	scanner.Scan()
	kullaniciAdi := scanner.Text()

	fmt.Println("Şifrenizi girin:")
	scanner.Scan()
	sifre := scanner.Text()

	kullanici, varMi := kullanicilar[kullaniciAdi]
	if varMi && kullanici.sifre == sifre {
		fmt.Printf("%s olarak giriş yaptınız.\n", kullanici.rol)
		kayitEkle(kullanici.rol + " yetkisinde başarılı giriş: " + kullaniciAdi)

		if kullanici.rol == "admin" {
			adminMenusu(scanner)
		} else {
			musteriMenusu(scanner, kullaniciAdi)
		}
	} else {
		fmt.Println("Geçersiz giriş")
		kayitEkle("Hatalı giriş denemesi: " + kullaniciAdi)
	}
}

func adminMenusu(scanner *bufio.Scanner) {
	for {
		fmt.Println("1 - Müşteri ekle")
		fmt.Println("2 - Müşteri sil")
		fmt.Println("3 - Log listele")
		fmt.Println("0 - Çıkış yap")
		scanner.Scan()
		secim := scanner.Text()

		switch secim {
		case "1":
			musteriEkle(scanner)
		case "2":
			musteriSil(scanner)
		case "3":
			logListele()
		case "0":
			fmt.Println("Çıkış yapıldı.")
			return
		default:
			fmt.Println("Geçersiz seçim")
		}
	}
}

func musteriMenusu(scanner *bufio.Scanner, kullaniciAdi string) {
	for {
		fmt.Println("1 - Profil görüntüle")
		fmt.Println("2 - Şifre değiştir")
		fmt.Println("0 - Çıkış yap")
		scanner.Scan()
		secim := scanner.Text()

		switch secim {
		case "1":
			fmt.Println("Bilgilerini görmek istediğiniz kullanıcı adını girin:")
			scanner.Scan()
			aramaKullaniciAdi := scanner.Text()

			if arananKullanici, varMi := kullanicilar[aramaKullaniciAdi]; varMi {
				fmt.Println("Kullanıcı Adı:", arananKullanici.kullaniciAdi)
				fmt.Println("Kullanıcı Türü:", arananKullanici.rol)
			} else {
				fmt.Println("Bu kullanıcı bulunamadı:", aramaKullaniciAdi)
			}
		case "2":
			sifreDegistir(scanner, kullaniciAdi)
		case "0":
			fmt.Println("Çıkış yapıldı.")
			return
		default:
			fmt.Println("Geçersiz seçim")
		}
	}
}

func musteriEkle(scanner *bufio.Scanner) {
	fmt.Println("Yeni müşteri kullanıcı adını girin:")
	scanner.Scan()
	kullaniciAdi := scanner.Text()

	fmt.Println("Şifre girin:")
	scanner.Scan()
	sifre := scanner.Text()

	kullanicilar[kullaniciAdi] = Kullanici{kullaniciAdi, sifre, "musteri"}
	fmt.Println("Müşteri eklendi:", kullaniciAdi)
	kayitEkle("Yeni müşteri eklendi: " + kullaniciAdi)
	kullanicilariKaydet()
}

func musteriSil(scanner *bufio.Scanner) {
	fmt.Println("Silmek istediğiniz müşteri kullanıcı adını girin:")
	scanner.Scan()
	kullaniciAdi := scanner.Text()

	if _, varMi := kullanicilar[kullaniciAdi]; varMi {
		delete(kullanicilar, kullaniciAdi)
		fmt.Println("Müşteri silindi:", kullaniciAdi)
		kayitEkle("Müşteri silindi: " + kullaniciAdi)
		kullanicilariKaydet()
	} else {
		fmt.Println("Müşteri bulunamadı:", kullaniciAdi)
	}
}

func sifreDegistir(scanner *bufio.Scanner, kullaniciAdi string) {
	fmt.Println("Yeni şifrenizi girin:")
	scanner.Scan()
	yeniSifre := scanner.Text()
	kullanicilar[kullaniciAdi] = Kullanici{kullaniciAdi, yeniSifre, "musteri"}
	fmt.Println("Şifre değiştirildi.")
	kayitEkle(kullaniciAdi + " adlı müşteri şifresini değiştirdi")
	kullanicilariKaydet()
}

func logListele() {
	dosya, err := os.Open("log.txt")
	if err != nil {
		fmt.Println("Log dosyası açılamadı:", err)
		return
	}
	defer dosya.Close()

	scanner := bufio.NewScanner(dosya)
	fmt.Println("Log Kayıtları:")
	for scanner.Scan() {
		fmt.Println(scanner.Text())
	}
}

func kayitEkle(eylem string) {
	dosya, err := os.OpenFile("log.txt", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
	if err != nil {
		fmt.Println("Log dosyasına yazılamadı:", err)
		return
	}
	defer dosya.Close()

	log := fmt.Sprintf("%s: %s\n", time.Now().Format("2006-01-02 15:04:05"), eylem)
	if _, err := dosya.WriteString(log); err != nil {
		fmt.Println("Log dosyasına yazılamadı:", err)
	}
}

func kullanicilariKaydet() {
	dosya, err := os.OpenFile("kullanicilar.txt", os.O_TRUNC|os.O_CREATE|os.O_WRONLY, 0644)
	if err != nil {
		fmt.Println("Kullanıcı dosyasına yazılamadı:", err)
		return
	}
	defer dosya.Close()

	for _, kullanici := range kullanicilar {
		satir := fmt.Sprintf("%s,%s,%s\n", kullanici.kullaniciAdi, kullanici.sifre, kullanici.rol)
		dosya.WriteString(satir)
	}
}

func main() {
	kullanicilariYukle()
	scanner := bufio.NewScanner(os.Stdin)
	girisYap(scanner)
}
