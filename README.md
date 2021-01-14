# improse
Image Processing Service

## Setup

### Installation

```shell
$ composer install
```

### Development environment

Run service using Docker
```shell
$ docker build -t 14code_improse .
$ docker run -d --rm -v "$PWD":/app -w /app -p 8081:8080 14code_improse
```

Open in browser: http://localhost:8081/

