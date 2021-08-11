function randomtFromInterval(min, max) { // min and max included 
    return Math.floor(Math.random() * (max - min + 1) + min)
  }

function demo_generateDistributionData(){
    var dist = [];
    for(var i = 0; i < randomtFromInterval(10,30); i++){
        dist.push({
            "name":"Realm" + i,
            "count":randomtFromInterval(0,50000)
        });
    }
    return dist;
}

function getDataFromUrl(callback) {
    var jqxhr = $.getJSON( "/data/xivdata.json", function() {
        console.log( "success" );
      })
      .done(function(json) {
         console.log( "second success" );
         callback(json)
       })
        .fail(function() {
          console.log( "error" );
        })
        .always(function() {
          console.log( "complete" );
        });      
}

function getData()
{
    return {
        "characters": {
            "all":517551,
            "active":51578,
            "deleted":2062549
        },
        "realms":{
            "america": {
                "all": {
                    "count": 229265,
                    "distribution" : demo_generateDistributionData()
                },
                "active": {
                    "count": 21504,
                    "distribution": demo_generateDistributionData()
                }
            },
            "japan": {
                "all": {
                    "count": 195536,
                    "distribution" : demo_generateDistributionData()
                },
                "active": {
                    "count": 23679,
                    "distribution": demo_generateDistributionData()
                }
            },
            "europe": {
                "all": {
                    "count": 92750,
                    "distribution" : demo_generateDistributionData()
                },
                "active": {
                    "count": 6395,
                    "distribution": demo_generateDistributionData()
                }
            }
        },
        "racedistribution": {
            "all" : [
                {
                    "name":"Au Ra",
                    "male":14148,
                    "female":26360
                },
                {
                    "name":"Miqote",
                    "male":1245,
                    "female":45645
                }
            ],
            "active" : [
                {
                    "name":"Au Ra",
                    "male":14148,
                    "female":26360
                },
                {
                    "name":"Miqote",
                    "male":1245,
                    "female":45645
                }
            ]
        },
        "jobs": {
            "all": [
                {
                "name":"Gladiator",
                "count":20000,
                "role":"tank"
                },
                {
                    "name":"Pugilist",
                    "count":16000,
                    "role":"dps"
                    }
            ],
            "active": [
                {
                "name":"Gladiator",
                "count":10000,
                "role":"tank"
                }
            ]
        },
        "grandcompany": {
            "all": {
                "immortalflames":133000,
                "maelstrom":123000,
                "twinadder":124000,
                "none":600000
            },
            "active": {
                "immortalflames":133000,
                "maelstrom":123000,
                "twinadder":124000,
                "none": 1962
            }
        }
    }
}

//on document ready
$(function() {
    getDataFromUrl(function(data){
    console.log(data);

    if(true)//gathering is in process
    {
        var progress = (data.characters.all / 50000000.0); //temporary until this is provided by the backend
        $("#infobox-progress-label").text("Progress: " + parseFloat(progress.toFixed(4)) + "%");
    }

    $("#label_player_count").text(data.characters.all.toLocaleString('en'));
    $("#label_active_player_count").text(data.characters.active.toLocaleString('en'));

    $("#label_realm_america_total").text(data.realms.america.all.count.toLocaleString('en'));
    $("#label_realm_japan_total").text(data.realms.japan.all.count.toLocaleString('en'));
    $("#label_realm_europe_total").text(data.realms.europe.all.count.toLocaleString('en'));

    $("#label_realm_america_active").text(data.realms.america.active.count.toLocaleString('en'));
    $("#label_realm_japan_active").text(data.realms.japan.active.count.toLocaleString('en'));
    $("#label_realm_europe_active").text(data.realms.europe.active.count.toLocaleString('en'));

    createPopulationChart($("#div_population_america_all")[0],data.realms.america.all.distribution)
    createPopulationChart($("#div_population_america_active")[0],data.realms.america.active.distribution)

    createPopulationChart($("#div_population_japan_all")[0],data.realms. japan.all.distribution)
    createPopulationChart($("#div_population_japan_active")[0],data.realms.japan.active.distribution)

    createPopulationChart($("#div_population_europe_all")[0],data.realms.europe.all.distribution)
    createPopulationChart($("#div_population_europe_active")[0],data.realms.europe.active.distribution)

    createGenderRaceChart($("#div_population_race_all")[0],data.racedistribution.all)
    createGenderRaceChart($("#div_population_race_active")[0],data.racedistribution.active)

    createJobChart($("#div_population_job_all")[0],data.jobs.all)
    createJobChart($("#div_population_job_active")[0],data.jobs.active)

    createGrandCompanyChart($("#div_population_gc_all")[0],data.grandcompany.all)
    createGrandCompanyChart($("#div_population_gc_active")[0],data.grandcompany.active)

    createBeastTribesChart($("#div_beast_tribes")[0],data.beasttribes)
});
});

function createGrandCompanyChart(div, data){
    var labels = ["None", "Immortal Flames", "Maelstrom", "Order of the Twin Adder"];
    var values = [data.none, data.immortalflames, data.maelstrom, data.twinadder];
    var colors = [
        'rgba(158, 158, 158)',
        'rgba(33, 33, 33)',
        'rgba(183, 28, 28)',
        'rgba(255, 193, 7)'
    ]

    var vv = [];
    for(var index = 0; index < values.length; index++){
        var valueobj = {
            "value": values[index],
            "name": labels[index],
            "itemStyle": {
                "color": colors[index]
            }
        };
        vv.push(valueobj);
    }

    option = {
        tooltip: {
            trigger: 'item'
        },
        textStyle: {
            color: "#fff"
        },
        series: [
            {
                type: 'pie',
                radius: '80%',
                data: vv,
                label: {
                    color: '#fff'
                },
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    var myChart = echarts.init(div);
    option && myChart.setOption(option);
}

function createGenderRaceChart(div,data){
    var labels = [];
    var valuesMale = [];
    var valuesFemale = [];

    for(var i = 0; i < data.length; i++)
    {
        labels.push(data[i].name);
        valuesMale.push(data[i].male);
        valuesFemale.push(data[i].female);
    }

    var myChart = echarts.init(div);
    var option;

option = {
    tooltip: createBarChartTooltip(),
    legend: {
        data: ['# of Females', '# of Males'],
        textStyle: {
            color: 'rgba(255,255,255,0.7)',
            fontSize: 16
        }
    },
    textStyle: {
        color: 'rgba(255, 255, 255, 0.9)',
        fontSize: 32,
    },
    grid: createBarChartGrid(),
    xAxis: createXAxis(labels, false),
    yAxis: createYAxis(),
    series: [
        {
            name: '# of Females',
            type: 'bar',
            data: valuesFemale,
            color: 'rgba(158, 0, 0)'
        },
        {
            name: '# of Males',
            type: 'bar',
            data: valuesMale,
            color: 'rgba(3, 155, 229)'
        }
    ]
    };
    option && myChart.setOption(option);
}


function createPopulationChart(div, populationData)
{

    var labels = [];
    var values = [];

    for(var i = 0; i < populationData.length; i++)
    {
        labels.push(populationData[i].name);
        values.push(populationData[i].count);
    }

    var myChart = echarts.init(div);
    var option;

option = {
    color: ["rgba(112, 38, 112)"],
    tooltip: createBarChartTooltip(),
    textStyle: {
        color: 'rgba(255, 255, 255, 0.7)',
        fontSize: 32,
    },
    grid: createBarChartGrid(),
    xAxis: createXAxis(labels,true),
    yAxis: createYAxis(),
    series: [
        {
            name: '# of Characters',
            type: 'bar',
            barWidth: '60%',
            data: values,
        }
    ]
};
option && myChart.setOption(option);
}

function createJobChart(div, jobData){
    var labels = [];
    var values = [];
    //var colors = [];

    for(var i = 0; i < jobData.length; i++)
    {
        labels.push(jobData[i].name);
        var vv = {}
        vv.value = jobData[i].count;
        vv.itemStyle = {};
        switch(jobData[i].role){
            case "tank":
                vv.itemStyle.color = 'rgb(61, 81, 177)';
                break;
            case "dps":
                vv.itemStyle.color = 'rgb(120, 53, 54)';
                break;
            case "healer":
                vv.itemStyle.color = 'rgb(61, 104, 48)';
                break;
            case "crafting":
                vv.itemStyle.color = 'rgb(103, 78, 160)';
                break;
            case "gathering":
                vv.itemStyle.color = 'rgb(168, 141, 59)';
                break;
            default:
                vv.itemStyle.color = 'rgb(0,0,0)';
                break;
        }
        values.push(vv);
    }

    var myChart = echarts.init(div);
    var option;

option = {
    //color: colors,
    tooltip: createBarChartTooltip(),
    textStyle: {
        color: 'rgba(255, 255, 255, 0.7)',
        fontSize: 32,
    },
    grid: createBarChartGrid(),
    xAxis: createXAxis(labels,true),
    yAxis: createYAxis(),
    series: [
        {
            name: '# of Characters',
            type: 'bar',
            barWidth: '60%',
            data: values,
        }
    ]
};
option && myChart.setOption(option);
}

function createBeastTribesChart(div, tribesData)
{

    var labels = [];
    var values = [];

    for(var tribe in tribesData) {
        labels.push(tribe);
        values.push(tribesData[tribe]);
    }

    var myChart = echarts.init(div);
    var option;

option = {
    color: ["rgba(112, 38, 112)"],
    tooltip: createBarChartTooltip(),
    textStyle: {
        color: 'rgba(255, 255, 255, 0.7)',
        fontSize: 32,
    },
    grid: createBarChartGrid(),
    xAxis: createXAxis(labels,true),
    yAxis: createYAxis(),
    series: [
        {
            name: '# of Characters',
            type: 'bar',
            barWidth: '60%',
            data: values,
        }
    ]
};
option && myChart.setOption(option);
}

function createYAxis() {
    return [
        {
            type: 'value',
            axisLabel: {
                fontSize: 15,
                formatter: function(value, index){
                    return humanFormat(value, {
                        separator: ''
                    })
                }
            }
        }
    ];
}

function createBarChartGrid() {
    return {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    }
}

function createXAxis(labels, rotateLabels){
    return [
        {
            type: 'category',
            data: labels,
            axisTick: {
                alignWithLabel: true
            },
            axisLabel: {
                fontSize: 12,
                interval: 0,
                rotate: rotateLabels ? 30 : 0,
                margin: 12
            }
        }
    ]
}

function createBarChartTooltip() {
    return {
        trigger: 'axis',
        axisPointer: {
            type: 'shadow'
        }
    }
}