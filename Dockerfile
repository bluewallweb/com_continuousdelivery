FROM ubuntu:latest

RUN apt update && apt install -y curl

CMD ["bash"]
