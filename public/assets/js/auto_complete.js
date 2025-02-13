var AutoCountry = {};
AutoCountry = function() {
	this.minChars = 2;
	this.field = null;
	this.countryLoopId = 0;
	this.helper = null;
	this.helperContent = "";
}
AutoCountry.prototype = {
	init:function(idOfTheField) {
		this.field = document.getElementById(idOfTheField);
		if(!this.field) {
			alert("Wrong input !");
		} else {
			this.createHelper();
			this.field.onfocus = this.onFieldIn;
			this.field.onblur = this.onFieldOut;
		}
	},
	onFieldIn:function() {
		AC.loop();
	},
	onFieldOut:function() {
		clearTimeout(AC.countryLoopId);
		setTimeout("AC.hideHelper()", 600);
	},
	loop:function() {
		var list = "";
		var value = AC.field.value;
		if(value.length >= this.minChars) {
			var numOfCountries = countries.length;
			for(var i=0; i<numOfCountries; i++) {
				if(value.toLowerCase() == countries[i].substr(0, value.length).toLowerCase()) {
					list += '<a href="javascript:AC.setCountry(\'' + countries[i] + '\');">' + countries[i] + '</a>'
				}
			}
		}
		if(list != "") {
			if(this.helperContent != list) {
				this.helperContent = list;
				this.helper.innerHTML = this.helperContent;
			}
			this.showHelper();
		} else {
			this.hideHelper();
		}
		AC.countryLoopId = setTimeout("AC.loop()", 200);
	},
	setCountry:function(country) {
		this.field.value = country;
		this.hideHelper();
	},
	// helper
	createHelper:function() {
		this.helper = document.createElement("div");
		this.helper.style.width = (this.field.offsetWidth - 22) + "px";
		this.helper.setAttribute("id", "helper");
		this.helper.innerHTML = "";
		document.body.appendChild(this.helper);
		this.positionHelper();
		this.hideHelper();
	},
	positionHelper:function() {
		var position = {x:0, y:0};
		var e = this.field;
		while(e) {
			position.x += e.offsetLeft;
			position.y += e.offsetTop;
			e = e.offsetParent;
		}
		this.helper.style.left = position.x + "px";
		this.helper.style.top = (position.y + this.field.offsetHeight)+ "px";
	},
	showHelper:function() {
		this.helper.style.display = "block";
	},
	hideHelper:function() {
		this.helper.style.display = "none";
	}
}

var AC = new AutoCountry();

var countries = [
	// Africa
	"Algeria",
	"Angola",
	"Benin",
	"Botswana",
	"Burkina",
	"Burundi",
	"Cameroon",
	"Cape Verde",
	"Central African Republic",
	"Chad",
	"Comoros",
	"Congo",
	"Djibouti",
	"Egypt",
	"Equatorial Guinea",
	"Eritrea",
	"Ethiopia",
	"Gabon",
	"Gambia",
	"Ghana",
	"Guinea",
	"Guinea-Bissau",
	"Ivory Coast",
	"Kenya",
	"Lesotho",
	"Liberia",
	"Libya",
	"Madagascar",
	"Malawi",
	"Mali",
	"Mauritania",
	"Mauritius",
	"Morocco",
	"Mozambique",
	"Namibia",
	"Niger",
	"Nigeria",
	"Rwanda",
	"Sao Tome and Principe",
	"Senegal",
	"Seychelles",
	"Sierra Leone",
	"Somalia",
	"South Africa",
	"Sudan",
	"Swaziland",
	"Tanzania",
	"Togo",
	"Tunisia",
	"Uganda",
	"Zambia",
	"Zimbabwe",
	// Asia
	"Afghanistan",
	"Bahrain",
	"Bangladesh",
	"Bhutan",
	"Brunei",
	"Burma (Myanmar)",
	"Cambodia",
	"China",
	"East Timor",
	"India",
	"Indonesia",
	"Iran",
	"Iraq",
	"Israel",
	"Japan",
	"Jordan",
	"Kazakhstan",
	"Korea (north)",
	"Korea (south)",
	"Kuwait",
	"Kyrgyzstan",
	"Laos",
	"Lebanon",
	"Malaysia",
	"Maldives",
	"Mongolia",
	"Nepal",
	"Oman",
	"Pakistan",
	"Philippines",
	"Qatar",
	"Russian Federation",
	"Saudi Arabia",
	"Singapore",
	"Sri Lanka",
	"Syria",
	"Tajikistan",
	"Thailand",
	"Turkey",
	"Turkmenistan",
	"United Arab",
	"dot Emirates",
	"Uzbekistan",
	"Vietnam",
	"Yemen",
	// Europe
	"Albania",
	"Andorra",
	"Armenia",
	"Austria",
	"Azerbaijan",
	"Belarus",
	"Belgium",
	"Bosnia and Herzegovina",
	"Bulgaria",
	"Croatia",
	"Cyprus",
	"Czech Republic",
	"Denmark",
	"Estonia",
	"Finland",
	"France",
	"Georgia",
	"Germany",
	"Greece",
	"Hungary",
	"Iceland",
	"Ireland",
	"Italy",
	"Latvia",
	"Liechtenstein",
	"Lithuania",
	"Luxembourg",
	"Macedonia",
	"Malta",
	"Moldova",
	"Monaco",
	"Montenegro",
	"Netherlands",
	"Norway",
	"Poland",
	"Portugal",
	"Romania",
	"San Marino",
	"Serbia",
	"Slovakia",
	"Slovenia",
	"Spain",
	"Sweden",
	"Switzerland",
	"Ukraine",
	"United Kingdom",
	"Vatican City",
	// North America
	"Antigua and Barbuda",
	"Bahamas",
	"Barbados",
	"Belize",
	"Canada",
	"Costa Rica",
	"Cuba",
	"Dominica",
	"Dominican Rep.",
	"El Salvador",
	"Grenada",
	"Guatemala",
	"Haiti",
	"Honduras",
	"Jamaica",
	"Mexico",
	"Nicaragua",
	"Panama",
	"St. Kitts & Nevis",
	"St. Lucia",
	"St. Vincent & the Grenadines",
	"Trinidad & Tobago",
	"United States",
	// Oceania
	"Australia",
	"Fiji",
	"Kiribati",
	"Marshall Islands",
	"Micronesia",
	"Nauru",
	"New Zealand",
	"Palau",
	"Papua New Guinea",
	"Samoa",
	"Solomon Islands",
	"Tonga",
	"Tuvalu",
	"Vanuatu",
	// South America
	"Argentina",
	"Bolivia",
	"Brazil",
	"Chile",
	"Colombia",
	"Ecuador",
	"Guyana",
	"Paraguay",
	"Peru",
	"Suriname",
	"Uruguay",
	"Venezuela"
];


