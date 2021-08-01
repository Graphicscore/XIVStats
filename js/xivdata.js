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
    var data = getData();
    console.log(data);
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

    /*createGenderRaceChart($("#canvas_population_race_all")[0].getContext('2d'),data.racedistribution.all)
    createGenderRaceChart($("#canvas_population_race_active")[0].getContext('2d'),data.racedistribution.active)

    createJobChart($("#canvas_population_job_all")[0].getContext('2d'),data.jobs.all)
    createJobChart($("#canvas_population_job_active")[0].getContext('2d'),data.jobs.active)

    createGrandCompanyChart($("#canvas_population_gc_all")[0].getContext('2d'),data.grandcompany.all)
    createGrandCompanyChart($("#canvas_population_gc_active")[0].getContext('2d'),data.grandcompany.active)*/
});

function createBarChartOptions() {
    return {
        grouped: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: "#fff",
                    usePointStyle:true
                }
            },
            tooltip: {
                
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: "#fff",
                    drawBorder: false
                },
                ticks: {
                    color: "#fff",
                    padding: 14,
                    maxTicksLimit: 9,
                    font: {
                        size: 16
                    },
                    callback: function(value, index, values) {
                        //var result = Math.floor(value/100)*100;
                        return humanFormat(value, {
                            separator: ''
                          })
                    }
                },
                title: {
                    display: true,
                    color: "#c3ac5c",
                    font: {
                        size: 16
                    },
                    text: "# of Characters"
                }
            },
            x: {
                grid: {
                    display: false,
                },
                ticks: {
                    color: "#fff",
                    font: {
                        size: 14
                    }
                },
                title: {
                    align:'start',
                    display: true,
                    color: "#fff"
                }
            }
        }
    };
}

function getToolTipOptions() {
    return {
        xAlign: "center",
        yAlign: "center" 
      };
}

function createGrandCompanyChart(ctx, data){
    var labels = ["None", "Immortal Flames", "Maelstrom", "Order of the Twin Adder"];
    var values = [data.none, data.immortalflames, data.maelstrom, data.twinadder];
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [
                {
                label: '# of Characters',
                data: values,
                backgroundColor: [
                    'rgba(158, 158, 158)',
                    'rgba(33, 33, 33)',
                    'rgba(183, 28, 28)',
                    'rgba(255, 193, 7)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
              },
              title: {
                display: true,
                text: 'Chart.js Pie Chart'
              },
              tooltip: getToolTipOptions()
            },
            animation: {
                animateScale: true,
            }
          }
    });
}

function createGenderRaceChart(ctx,data){
    var labels = [];
    var valuesMale = [];
    var valuesFemale = [];

    for(var i = 0; i < data.length; i++)
    {
        labels.push(data[i].name);
        valuesMale.push(data[i].male);
        valuesFemale.push(data[i].female);
    }

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                label: '# of Males',
                data: valuesMale,
                backgroundColor: [
                    'rgba(3, 155, 229)'
                ],
                borderWidth: 0
            }, {
                label: '# of Females',
                data: valuesFemale,
                backgroundColor: [
                    'rgba(158, 0, 0)'
                ],
                borderWidth: 0
            }]
        },
        options: createBarChartOptions()
    });
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
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'shadow'
        }
    },
    textStyle: {
        color: 'rgba(255, 255, 255, 0.7)',
        fontSize: 32,
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    xAxis: [
        {
            type: 'category',
            data: labels,
            axisTick: {
                alignWithLabel: true
            },
            axisLabel: {
                fontSize: 12,
                rotate: 30,
                interval: 0,
                margin: 12
            }
        }
    ],
    yAxis: [
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
    ],
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

function createJobChart(ctx, jobData){
    var labels = [];
    var values = [];
    var colors = [];

    for(var i = 0; i < jobData.length; i++)
    {
        labels.push(jobData[i].name);
        values.push(jobData[i].count);
        switch(jobData[i].role){
            case "tank":
                colors.push('rgb(61, 81, 177)')
                break;
            case "dps":
                colors.push('rgb(120, 53, 54)')
                break;
            case "healer":
                colors.push('rgb(61, 104, 48)')
                break;
            case "crafter":
                colors.push('rgb(103, 78, 160)')
                break;
            case "gatherer":
                colors.push('rgb(168, 141, 59)')
                break;
            default:
                colors.push('rgb(0,0,0)')
                break;
        }
    }

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: '# of Characters',
                data: values,
                backgroundColor: colors,
                borderWidth: 0
            }]
        },
        options: createBarChartOptions()
    });
}