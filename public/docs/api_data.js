define({ "api": [
  {
    "type": "post",
    "url": "/instance/alerts/dismiss/:instanceId",
    "title": "Dismiss Alert",
    "name": "DismissAlert",
    "description": "<p>Dismiss an alert by passing in an alerted instance's ID.</p>",
    "group": "Alerts",
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Instance/AlertController.php",
    "groupTitle": "Alerts"
  },
  {
    "type": "get",
    "url": "/instance/alerts",
    "title": "Get Alerts",
    "name": "GetAlerts",
    "description": "<p>Get the current user's alerts.</p>",
    "group": "Alerts",
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Instance/AlertController.php",
    "groupTitle": "Alerts",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Boolean",
            "optional": true,
            "field": "dismissed",
            "description": "<p>Pass dismissed as true to include dismissed alerts in results.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "vectors_name",
            "description": "<p>Name of vector to get instances for.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "start_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss (2016-06-07 17:54:15)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "end_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss (2016-06-07 17:54:15)</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"data\": {[\n        {\n            \"id\" => \"123\",\n            \"title\" => \"Some Title\",\n            \"company\" => \"Company name\",\n            \"vector\" => \"Vector\", // Can be ''\n            \"vector_color\" => \"#000000\",\n            \"type\" => \"Instance Type\",\n            \"date\" => \"2016-04-12 12:23:23\",\n            \"language\" => \"eng\",\n            \"source\" => \"Some Source\",\n            \"fragment\" => \"A string about the instance\",\n            \"link\" => \"A URL to the instance\",\n            \"regions\" => \"North America, Africa\",\n            \"positive_risk_score\" => \"10\",\n            \"negative_risk_score\" => \"0\",\n            \"risk_score\" => \"10\",\n            \"flagged\" => false\n        },\n        {\n            \"id\" => \"456\",\n            \"title\" => \"Some Title\",\n            \"company\" => \"Company name\",\n            \"vector\" => \"Vector\", // Can be ''\n            \"vector_color\" => \"#000000\",\n            \"type\" => \"Instance Type\",\n            \"date\" => \"2016-04-12 12:23:23\",\n            \"language\" => \"eng\",\n            \"source\" => \"Some Source\",\n            \"fragment\" => \"A string about the instance\",\n            \"link\" => \"A URL to the instance\",\n            \"regions\" => \"Europe, South America\",\n            \"positive_risk_score\" => \"20\",\n            \"negative_risk_score\" => \"50\",\n            \"risk_score\" => \"-30\",\n            \"flagged\" => true\n        }\n    ]},\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/company/logo",
    "title": "Company Logo",
    "name": "CompanyLogo",
    "description": "<p>Return the current company's logo image.</p>",
    "group": "Company",
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Admin/Company/CompanyController.php",
    "groupTitle": "Company"
  },
  {
    "type": "get",
    "url": "/instance/",
    "title": "List instances",
    "name": "ListInstances",
    "description": "<p>List instances based on query parameters.</p>",
    "group": "Instances",
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Instance/QueryController.php",
    "groupTitle": "Instances",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "start_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss (2016-06-07 17:54:15)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss (2016-06-07 17:54:15)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "vectors_name",
            "description": "<p>Name of vector to get instances for.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "regions_name",
            "description": "<p>Name of region to get instances for.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "size": "1-1000",
            "optional": true,
            "field": "count",
            "description": "<p>The maximum number of records per page to be retrieved with pagination.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "page",
            "description": "<p>The page of the result set to return.</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Example request:",
        "content": "{\n    start_datetime: \"2015-03-1 00:00:00\",\n    end_datetime: \"2015-5-1 00:00:00\",\n    vectors_name: \"Vector\",\n    regions_name: \"Region\"\n}",
        "type": "json"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"data\": {[\n        {\n            \"id\" => \"123\",\n            \"title\" => \"Some Title\",\n            \"company\" => \"Company name\",\n            \"vector\" => \"Vector\", // Can be ''\n            \"vector_color\" => \"#000000\",\n            \"type\" => \"Instance Type\",\n            \"date\" => \"2016-04-12 12:23:23\",\n            \"language\" => \"eng\",\n            \"source\" => \"Some Source\",\n            \"fragment\" => \"A string about the instance\",\n            \"link\" => \"A URL to the instance\",\n            \"regions\" => \"North America, Africa\",\n            \"positive_risk_score\" => \"10\",\n            \"negative_risk_score\" => \"0\",\n            \"risk_score\" => \"10\",\n            \"flagged\" => false\n        },\n        {\n            \"id\" => \"456\",\n            \"title\" => \"Some Title\",\n            \"company\" => \"Company name\",\n            \"vector\" => \"Vector\", // Can be ''\n            \"vector_color\" => \"#000000\",\n            \"type\" => \"Instance Type\",\n            \"date\" => \"2016-04-12 12:23:23\",\n            \"language\" => \"eng\",\n            \"source\" => \"Some Source\",\n            \"fragment\" => \"A string about the instance\",\n            \"link\" => \"A URL to the instance\",\n            \"regions\" => \"Europe, South America\",\n            \"positive_risk_score\" => \"20\",\n            \"negative_risk_score\" => \"50\",\n            \"risk_score\" => \"-30\",\n            \"flagged\" => true\n        }\n    ]},\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
          "type": "json"
        },
        {
          "title": "Results with pagination:",
          "content": "// Any endpoint will have the following \"meta\" key added to the response. Also the endpoint\n// may have the following parameter to determine the number of records returned.\n\n\"meta\": [\n    \"pagination\" => [\n        \"total\" => 10,\n        \"count\" => 3,\n        \"per_page\" => 3,\n        \"current_page\" => 2,\n        \"total_pages\" => 4,\n        \"links\" => [\n            \"previous\" => \"http://optoview/api/{endpoint_url}?page=1\"\n            \"next\" => \"http://optoview.com/api/{endpoint_url}?page=3\"\n        ]\n    ]\n]",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/competitors-average-risk-score",
    "title": "Risk Score",
    "name": "RiskScore",
    "description": "<p>Return the risk score for a company, and industry average risk score.</p>",
    "group": "Instances",
    "examples": [
      {
        "title": "Success-Response:",
        "content": "HTTP/1.1 200 OK\n{\n    \"data\":[\n        {\"company_risk_score\":25},\n        {\"average_competitor_risk_score\":10}\n    ],\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
        "type": "json"
      }
    ],
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Instance/QueryController.php",
    "groupTitle": "Instances",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "company_name",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "Number",
            "allowedValues": [
              "7",
              "30",
              "186",
              "365"
            ],
            "optional": false,
            "field": "lastDays",
            "description": "<p>(Required if start_datetime not given)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "start_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss (Required if lastDays not given)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss (Required if lastDays not given)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "vector",
            "description": "<p>ID of vector</p>"
          }
        ]
      }
    }
  },
  {
    "type": "post",
    "url": "/riskScoreMapData",
    "title": "Risk Score map data",
    "name": "RiskScoreMapData",
    "description": "<p>Return the risk score data for risk map</p>",
    "group": "Instances",
    "examples": [
      {
        "title": "Success-Response:",
        "content": "HTTP/1.1 200 OK\n{\n    \"data\":[\n        {\n            \"region\":\"Region name\",\n            \"count\":15,\n            \"percent_change\":20,\n            \"risk\": \"medium\",\n            \"vectors\":[\n                {\n                    \"vector1\":\"vector name\",\n                    \"count\":10,\n                    \"risk\": \"medium\",\n                    \"percent_change\": 10\n                }\n                {\n                    \"vector1\":\"vector name\",\n                    \"count\":5,\n                    \"risk\": \"medium\"\n                    \"percent_change\": 10\n                }\n            ]\n        },\n    ],\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
        "type": "json"
      },
      {
        "title": "Example request:",
        "content": "{\n    start_datetime: \"2015-03-1 00:00:00\",\n    end_datetime: \"2015-5-1 00:00:00\"\n}",
        "type": "json"
      }
    ],
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Api/Instance/RiskScoreMapController.php",
    "groupTitle": "Instances",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "start_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/industry-risk-score-change",
    "title": "Industry Risk Score Change",
    "name": "CompetitorRiskScoreChange",
    "description": "<p>Return the risk score change for competitors over a period of time defined by start/end_datetime as a whole number percent.</p>",
    "group": "Risk_Score",
    "examples": [
      {
        "title": "Success-Response:",
        "content": "HTTP/1.1 200 OK\n{\n    \"data\":[\n        {\"change_percent\":25},\n    ],\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
        "type": "json"
      }
    ],
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Instance/QueryController.php",
    "groupTitle": "Risk_Score",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "start_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss (Required if lastDays not given)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss (Required if lastDays not given)</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/risk-score-change",
    "title": "Risk Score Change",
    "name": "RiskScoreChange",
    "description": "<p>Return the risk score change over a period of time defined by start/end_datetime as a whole number percent.</p>",
    "group": "Risk_Score",
    "examples": [
      {
        "title": "Success-Response:",
        "content": "HTTP/1.1 200 OK\n{\n    \"data\":[\n        {\"change_percent\":25},\n    ],\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
        "type": "json"
      }
    ],
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Instance/QueryController.php",
    "groupTitle": "Risk_Score",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "start_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss (Required if lastDays not given)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_datetime",
            "description": "<p>Acceptable format: YYYY-MM-DD HH:ii:ss (Required if lastDays not given)</p>"
          }
        ]
      }
    }
  },
  {
    "type": "post",
    "url": "/password/reset/",
    "title": "Reset Password",
    "name": "ResetPassword",
    "description": "<p>Starts password reset process, and sends mail to user to continue.</p>",
    "group": "Users",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>Email of user requesting password reset.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"data\": {\n        \"success\": \"Email successfully sent to email@email.com\"\n    },\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Auth/ApiPasswordController.php",
    "groupTitle": "Users"
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
          "content": "HTTP/1.1 200 OK\n{\n    \"data\": {\n        \"token\": \"SomeLongTokenString\",\n        \"earliest_instance_date\": \"2016-07-05\",\n        \"username\": \"user\",\n        \"company\": {\n            \"id\": 1,\n            \"name\": \"Company Name\",\n            \"entity_id\": \"AV4DTB\",\n            \"max_alert_threshold\": 90,\n            \"min_alert_threshold\": -90\n        }\n    },\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Auth/ApiAuthController.php",
    "groupTitle": "Users"
  },
  {
    "type": "post",
    "url": "/logout",
    "title": "Logout",
    "name": "UserLogout",
    "group": "Users",
    "description": "<p>Logs a user out.</p>",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"data\": {\n    },\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
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
          "content": "HTTP/1.1 200 OK\n{\n    \"data\": [\n        {\n            \"date\":\"2016-04\",\n            \"vectors\": [\n                {\"vector\":\"Vector 1\",\"value\": 10},\n                {\"vector\":\"Vector 2\",\"value\": 20},\n                {\"vector\":\"Vector 3\",\"value\": 30}\n            ]\n        },\n        {\n            \"date\":\"2016-04\",\n            \"vectors\": [\n                {\"vector\":\"Vector 1\",\"value\": 10},\n                {\"vector\":\"Vector 2\",\"value\": 20},\n                {\"vector\":\"Vector 3\",\"value\": 30}\n            ]\n        },\n        ...\n    ],\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
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
  },
  {
    "type": "get",
    "url": "/vectors/",
    "title": "List Vectors",
    "name": "Vectors",
    "description": "<p>Retrieves vector names and colors.</p>",
    "group": "Vectors",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"data\":[\n        {\"id\":1,\"name\":\"Social Responsibility\",\"color\":\"#4BE6A1\",\"default_color\":\"#4BE6A1\"},\n        {\"id\":2,\"name\":\"Influencers\",\"color\":\"#4BE6A1\",\"default_color\":\"#4BE6A1\"}\n    ],\n    \"status_code\": 200,\n    \"message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Vector/VectorController.php",
    "groupTitle": "Vectors"
  }
] });
