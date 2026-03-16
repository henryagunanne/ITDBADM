const express = require('express');
const server = express();

const bodyParser = require('body-parser');
server.use(express.json()); 
server.use(express.urlencoded({ extended: true }));

// Set up handlebars as view engine
const handlebars = require('express-handlebars');
const hbs = handlebars.create({
    extname: 'hbs',
    defaultLayout: 'index',
    helpers: {
        json: function(context) {
            return JSON.stringify(context || []);
        }
    }
});

// Configure view engine
server.set('view engine', 'hbs');
server.engine('hbs', hbs.engine);

// Serve static files from public directory
server.use(express.static('public'));

server.get('/', function(req, resp){
    resp.render('home',{
        layout: 'index',
        title: 'Home | Cool Beans'
    });
});

// Start Server
const port = process.env.PORT || 9090;
server.listen(port, function(){
    console.log('Listening at port '+port);
});