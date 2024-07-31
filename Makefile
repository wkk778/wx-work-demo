FROM=wechat-gateway
VERSION=1.0.0
PROJECT_PATH=$(shell cd "./$(dirname "$0")"; pwd)
build:
	docker build -t ${FROM}:${VERSION} .
run:
	docker run  -it --rm  -v ${PROJECT_PATH}/:/var/www -p 8080:8000 ${FROM}:${VERSION} php think run --host=0.0.0.0 --port=8000
exec:
	docker run -it --rm -v ${PROJECT_PATH}/:/var/www -p 8080:8000  ${FROM}:${VERSION} /bin/sh
