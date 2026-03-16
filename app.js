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

const sampleBeans = [
  [
    { name: "Arabica", price: 450 },
    { name: "Robusta", price: 350 },
    { name: "Liberica", price: 500 }
  ],
  [
    { name: "Excelsa", price: 480 },
    { name: "Colombian Supremo", price: 550 },
    { name: "Ethiopian Yirgacheffe", price: 600 }
  ]
];

server.get('/', function(req, resp){
    resp.render('home',{
        layout: 'index',
        title: 'Home | Cool Beans',
        beans: sampleBeans,
        ffact: {title: "Every purchase helps a local farmer!", description: "Some fact about farmers"},
        testimonials: [{title: "Ang sarap!", description: "Super!", author: "J. Daguiso"}, {title: "Ang sarap!", description: "Super!", author: "H. Agunanne"}, {title: "Ang sarap!", description: "Super!", author: "M. Andaya"}]
    });
});

// Start Server
const port = process.env.PORT || 9090;
server.listen(port, function(){
    console.log('Listening at port '+port);
});