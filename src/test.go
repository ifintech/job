package main

import (
	"fmt"
	"flag"
)

func main() {

	config_path := flag.String("f", "/etc/job-agent.json", "config file path")
	flag.Parse()

	fmt.Println(*config_path)
}
