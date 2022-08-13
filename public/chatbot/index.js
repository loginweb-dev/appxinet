const express = require('express');
const axios = require('axios');
const qrcode = require("qrcode-terminal");
const fs = require("fs");
require('dotenv').config();
const cors = require('cors')

const { Client, MessageMedia, LocalAuth} = require("whatsapp-web.js");
const SESSION_FILE_PATH = "./session.json";
let sessionData;

const app = express();
app.use(cors())

if (fs.existsSync(SESSION_FILE_PATH)) {
    sessionData = require(SESSION_FILE_PATH);
}
const client = new Client({
    authStrategy: new LocalAuth({
        clientId: "client-one"
    }),
    puppeteer: {
        ignoreDefaultArgs: ['--disable-extensions'],
        args: ['--no-sandbox']
    }
});
client.on("qr", (qr) => {
    qrcode.generate(qr, { small: true });
    console.log('Nuevo QR, recuerde que se genera cada 1/2 minuto.')
});
client.on('ready', () => {
    console.log('El BOT esta listo.');
	app.listen(process.env.PORT, () => {
		console.log('BOT CORRIENDO EN '+process.env.PORT);
	});
  
});
client.on("authenticated", (session) => {
    // sessionData = session;
    // console.log(session)
    // fs.writeFile(SESSION_FILE_PATH, JSON.stringify(session), function (err) {
    //     if (err) {
    //         console.error(err);
    //     }
    // });
});
client.on("auth_failure", msg => {
    console.error('AUTHENTICATION FAILURE', msg);
})

client.initialize();


app.get('/', async (req, res) => {
    let chatId = process.env.CODE_COUNTRY + req.query.phone + "@c.us";
    if (req.query.type == 'text') {
        client.sendMessage(chatId, req.query.message).then((response) => {
            if (response.id.fromMe) {
                console.log("text fue enviado!");   
            }
        })
    }else if (req.query.type == 'galery') {
        const media = MessageMedia.fromFilePath(req.query.attachment);
        client.sendMessage(chatId, media, {caption: req.query.message}).then((response) => {
            if (response.id.fromMe) {
                console.log("galery fue enviado!");
            }
        });
    }else if (req.query.type == 'pin') {
        client.sendMessage(chatId, req.query.message).then((response) => {
            if (response.id.fromMe) {
                console.log("pin fue enviado!");   
            }
        })
    }
    res.send('CHATBOT');
  });