const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const { OAuth2Client } = require('google-auth-library');

const CLIENT_ID = '402394936170-euum54ctdmei4k5f5du5j33oarau6fnq.apps.googleusercontent.com';
const CLIENT_SECRET = '{"web":{"client_id":"402394936170-euum54ctdmei4k5f5du5j33oarau6fnq.apps.googleusercontent.com","project_id":"medicalservice-385501","auth_uri":"https://accounts.google.com/o/oauth2/auth","token_uri":"https://oauth2.googleapis.com/token","auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs","client_secret":"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","javascript_origins":["http://localhost"]}}T';
const REDIRECT_URI = 'http://localhost:3000/login';
const oauth2Client = new OAuth2Client(CLIENT_ID, CLIENT_SECRET, REDIRECT_URI);

const app = express();
app.use(bodyParser.json());
app.use(cors());

app.post('/login', async (req, res) => {
  try {
    const { code } = req.body;

    const { tokens } = await oauth2Client.getToken(code);
    oauth2Client.setCredentials(tokens);

    res.json({ access_token: tokens.access_token });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

app.listen(3000, () => console.log('Server listening on port 3000'));
