FROM nginx:latest

RUN mkdir -p /var/keebsocial
RUN rm /etc/nginx/conf.d/default.conf
COPY nginx/default.conf /etc/nginx/conf.d/default.conf