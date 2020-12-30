FROM node:latest
RUN /usr/local/bin/npm install @wordpress/scripts --save-dev
RUN mkdir /app
WORKDIR /app

CMD npx wp-scripts format-js /app
