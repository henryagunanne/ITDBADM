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
    {
      bean_id: 0,
      bean_name: "Arabica",
      bean_image_path: "/common/coffee-bag.png",
      variety: "Typica",
      origin_province_id: 1,
      roast_level: "MEDIUM",
      price_per_kg: 450.00,
      supplier_id: 101,
      description: "Smooth and balanced with mild acidity and sweet notes."
    },
    {
      bean_id: 1,
      bean_name: "Robusta",
      bean_image_path: "/common/coffee-bag.png",
      variety: "Canephora",
      origin_province_id: 2,
      roast_level: "DARK",
      price_per_kg: 350.00,
      supplier_id: 102,
      description: "Strong, bold flavor with high caffeine content."
    },
    {
      bean_id: 2,
      bean_name: "Liberica",
      bean_image_path: "/common/coffee-bag.png",
      variety: "Barako",
      origin_province_id: 3,
      roast_level: "MEDIUM_DARK",
      price_per_kg: 500.00,
      supplier_id: 103,
      description: "Distinct smoky aroma with fruity undertones."
    }
  ],
  [
    {
      bean_id: 3,
      bean_name: "Excelsa",
      bean_image_path: "/common/coffee-bag.png",
      variety: "Excelsa",
      origin_province_id: 4,
      roast_level: "LIGHT",
      price_per_kg: 480.00,
      supplier_id: 104,
      description: "Tart, fruity profile often used in blends."
    },
    {
      bean_id: 4,
      bean_name: "Colombian Supremo",
      bean_image_path: "/common/coffee-bag.png",
      variety: "Supremo",
      origin_province_id: 5,
      roast_level: "MEDIUM",
      price_per_kg: 550.00,
      supplier_id: 105,
      description: "Rich flavor with caramel sweetness and mild acidity."
    },
    {
      bean_id: 5,
      bean_name: "Ethiopian Yirgacheffe",
      bean_image_path: "/common/coffee-bag.png",
      variety: "Heirloom",
      origin_province_id: 6,
      roast_level: "LIGHT",
      price_per_kg: 600.00,
      supplier_id: 106,
      description: "Floral aroma with citrus and tea-like body."
    }
  ]
];

server.get('/', function(req, resp){
    resp.render('home',{
        layout: 'index',
        title: 'Home | Cool Beans',
        beans: sampleBeans.slice(0, 1),
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
        .find(b => b.bean_id === id);

    // for debugging
    console.log(bean);
    
    resp.render('item',{
        layout: 'index',
        title: bean.bean_name + ' Beans | Cool Beans',
        selected: bean,
        beans: sampleBeans,
        location: 'Home > Beans > ' + bean.bean_name
    });
});

// Start Server
const port = process.env.PORT || 9090;
server.listen(port, function(){
    console.log('Listening at port '+port);
});