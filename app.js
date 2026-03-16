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
    { id: 0, name: "Arabica", price: 450 },
    { id: 1, name: "Robusta", price: 350 },
    { id: 2, name: "Liberica", price: 500 }
  ],
  [
    { id: 3, name: "Excelsa", price: 480 },
    { id: 4, name: "Colombian Supremo", price: 550 },
    { id: 5, name: "Ethiopian Yirgacheffe", price: 600 }
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

server.get('/view/beans', function(req, resp){
    resp.render('beans',{
        layout: 'index',
        title: 'Shop for Beans | Cool Beans',
        beans: sampleBeans,
        location: 'Home > Beans'
    });
});

server.get('/view/beans/:id', function(req, resp){
    const id = parseInt(req.params.id);

    const bean = sampleBeans
        .flat()
        .find(b => b.id === id);
    
    resp.render('item',{
        layout: 'index',
        title: bean.name + ' Beans | Cool Beans',
        selected: bean,
        location: 'Home > Beans > ' + bean.name
    });
});

// Start Server
const port = process.env.PORT || 9090;
server.listen(port, function(){
    console.log('Listening at port '+port);
});