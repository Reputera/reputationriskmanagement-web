define({ "api": [
  {
    "type": "get",
    "url": "/instances/",
    "title": "List instances",
    "name": "ListInstances",
    "description": "<p>List instances based on query parameters.</p>",
    "group": "Instances",
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Instance/QueryController.php",
    "groupTitle": "Instances",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"data\": {[\n        {\n            \"id\" => \"123\",\n            \"title\" => \"Some Title\",\n            \"company\" => \"Company name\",\n            \"vector\" => \"Vector\", // Can be ''\n            \"type\" => \"Instance Type\",\n            \"date\" => \"2016-04-12 12:23:23\",\n            \"language\" => \"eng\",\n            \"source\" => \"Some Source\",\n            \"fragment\" => \"A string about the instance\",\n            \"link\" => \"A URL to the instance\",\n            \"regions\" => \"North America, Africa\",\n            \"positive_risk_score\" => \"10\",\n            \"negative_risk_score\" => \"0\",\n            \"risk_score\" => \"10\",\n            \"flagged\" => false\n        },\n        {\n            \"id\" => \"456\",\n            \"title\" => \"Some Title\",\n            \"company\" => \"Company name\",\n            \"vector\" => \"Vector\", // Can be ''\n            \"type\" => \"Instance Type\",\n            \"date\" => \"2016-04-12 12:23:23\",\n            \"language\" => \"eng\",\n            \"source\" => \"Some Source\",\n            \"fragment\" => \"A string about the instance\",\n            \"link\" => \"A URL to the instance\",\n            \"regions\" => \"Europe, South America\",\n            \"positive_risk_score\" => \"20\",\n            \"negative_risk_score\" => \"50\",\n            \"risk_score\" => \"-30\",\n            \"flagged\" => true\n        }\n    ]},\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
          "type": "json"
        },
        {
          "title": "Results with pagination:",
          "content": "// Any endpoint will have the following \"meta\" key added to the response. Also the endpoint\n// may have the following parameter to determine the number of records returned.\n\n\"meta\": [\n    \"pagination\" => [\n        \"total\" => 10,\n        \"count\" => 3,\n        \"per_page\" => 3,\n        \"current_page\" => 2,\n        \"total_pages\" => 4,\n        \"links\" => [\n            \"previous\" => \"http://optoview/api/{endpoint_url}?page=1\"\n            \"next\" => \"http://optoview.com/api/{endpoint_url}?page=3\"\n        ]\n    ]\n]",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "size": "1-1000",
            "optional": true,
            "field": "limit",
            "description": "<p>The maximum number of records to be retrieved with pagination.</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/competitors-average-risk-score",
    "title": "Return the risk score for a company.",
    "name": "RiskScore",
    "description": "<p>Return the risk score for a company.</p>",
    "group": "Instances",
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Instance/QueryController.php",
    "groupTitle": "Instances"
  },
  {
    "type": "post",
    "url": "/login",
    "title": "Login",
    "name": "UserLogin",
    "group": "Users",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>The email of the user to login.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>the plain text password to use for login.</p>"
          }
        ]
      }
    },
    "description": "<p>Logs a user in with the given credentials.</p>",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"data\": {\"token\": \"SomeLongTokenString\"},\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Auth/ApiAuthController.php",
    "groupTitle": "Users"
  },
  {
    "type": "get",
    "url": "/vector-risk-scores-by-month/",
    "title": "Per month",
    "name": "VectorRiskScorePerMonth",
    "description": "<p>Retrieves risk score broken down for each year/month given for the customer's assigned company.</p>",
    "group": "Vectors",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"data\": {\n        \"2016-04\": {\n            \"Vector 1\": 10,\n            \"Vector 2\": 20,\n            \"Vector 3\": 30,\n        },\n        \"2016-7\": {\n            \"Vector 1\": 20,\n            \"Vector 2\": 30,\n            \"Vector 3\": 10,\n        },\n        ...\n    },\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Vector/MonthlyRiskScoreController.php",
    "groupTitle": "Vectors",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "dates",
            "description": "<p>Acceptable formats: 2015-04, 2015-4</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Example request:",
        "content": "{dates: [\"2015-03\", \"2015-4\"]}",
        "type": "json"
      }
    ],
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 422 Unprocessable Entity\n{\n    \"message\": \"Invalid request\",\n    \"status_code\": 422,\n    \"errors\":\n    {\n        \"dates\": [\n            \"The dates field is required.\"\n        ],\n        \"dates.0\": [ // Note the 0 will be the key of the failing array value.\n            \"The expected format is YYYY-MM.\"\n        ]\n    }\n}",
          "type": "json"
        }
      ]
    }
  }
] });
