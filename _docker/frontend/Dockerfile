FROM node:18-alpine

WORKDIR /var/www/frontend

COPY frontend/package*.json ./

RUN npm install

COPY frontend .

# RUN npm run build

EXPOSE 3000

# CMD ["npm", "run", "start"]
CMD ["npm", "run", "dev"]