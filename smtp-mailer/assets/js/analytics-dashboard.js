
var element = document.getElementById("analytics-users");
if (element !== null) {
  element.innerHTML = "";
  var spark1 = {
    chart: {
      type: "line",
      height: 60,
      width: 110,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: false,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0,
      },
    },
    grid: {
      show: false,
      xaxis: {
        lines: {
          show: false,
        },
      },
      yaxis: {
        lines: {
          show: false,
        },
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 2,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [0, 21, 54, 38, 56, 24, 65, 53, 67],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      show: false,
      axisTicks: {
        show: false,
      },
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    colors: ["rgba(142, 84, 233, 0.95)"],
  };
  var spark1 = new ApexCharts(document.querySelector("#analytics-users"), spark1);
  spark1.render();
}
function analyticsusers() {
  spark1.updateOptions({
    colors: ["rgba(" + myVarVal + ", 0.95)"],
  });
}

var element = document.getElementById("analytics-visitors");
if (element !== null) {
  element.innerHTML = "";
  var spark2 = {
    chart: {
      type: "bar",
      height: 60,
      width: 90,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: false,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 0,
        color: "#000",
        opacity: 0,
      },
    },
    grid: {
      show: false,
      xaxis: {
        lines: {
          show: false,
        },
      },
      yaxis: {
        lines: {
          show: false,
        },
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      colors: undefined,
      width: 0.5,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [0, 21, 54, 38, 56, 24, 65, 53, 67],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      show: false,
      axisTicks: {
        show: false,
      },
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    colors: ["#4876e6"],
  };
  var spark2 = new ApexCharts(document.querySelector("#analytics-visitors"), spark2);
  spark2.render();
}

var element = document.getElementById("Bounce-Rate");
if (element !== null) {
  element.innerHTML = "";
  var spark3 = {
    chart: {
      type: "line",
      height: 60,
      width: 110,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: false,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 0,
        color: "#000",
        opacity: 0,
      },
    },
    grid: {
      show: false,
      xaxis: {
        lines: {
          show: false,
        },
      },
      yaxis: {
        lines: {
          show: false,
        },
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 2,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [0, 21, 54, 38, 56, 24, 65, 53, 67],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      show: false,
      axisTicks: {
        show: false,
      },
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    colors: ["#4876e6"],
  };
  var spark3 = new ApexCharts(document.querySelector("#Bounce-Rate"), spark3);
  spark3.render();
}

var element = document.getElementById("Page-Views");
if (element !== null) {
  element.innerHTML = "";
  var spark4 = {
    chart: {
      type: "bar",
      height: 60,
      width: 90,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: false,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0.1,
      },
    },
    grid: {
      show: false,
      xaxis: {
        lines: {
          show: false,
        },
      },
      yaxis: {
        lines: {
          show: false,
        },
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 0.5,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [0, 21, 54, 38, 56, 24, 65, 53, 67],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      show: false,
      axisTicks: {
        show: false,
      },
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    colors: ["#f5b849"],
  };
  var spark4 = new ApexCharts(document.querySelector("#Page-Views"), spark4);
  spark4.render();
}

var element = document.getElementById("audienceReport");
if (element !== null) {
  element.innerHTML = "";
  var options = {
    series: [
      {
        name: "Sales",
        data: [43, 42, 56, 86, 58, 55, 70, 43, 23, 54, 77, 34],
      },
      {
        name: "OPEX Ratio",
        data: [74, 72, 87, 96, 78, 85, 100, 73, 53, 84, 107, 64],
      },
      {
        name: "General & Admin",
        data: [84, 82, 97, 126, 98, 95, 110, 83, 63, 94, 117, 74],
      },
      {
        name: "Marketing",
        type: "line",
        data: [34, 22, 36, 55, 21, 25, 58, 29, 49, 68, 90, 53],
      },
    ],
    chart: {
      stacked: true,
      type: "bar",
      height: 335,
    },
    grid: {
      borderColor: "#f5f4f4",
      strokeDashArray: 5,
    },
    plotOptions: {
      bar: {
        colors: {
          ranges: [
            {
              from: -100,
              to: -46,
              color: "#ebeff5",
            },
            {
              from: -45,
              to: 0,
              color: "#ebeff5",
            },
          ],
        },
        columnWidth: "22%",
      },
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: true,
      position: "top",
    },
    yaxis: {
      labels: {
        formatter: function (y) {
          return y.toFixed(0) + "";
        },
      },
    },
    xaxis: {
      type: "month",
      categories: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "sep",
        "oct",
        "nov",
        "dec",
      ],
      axisBorder: {
        show: false,
        color: "rgba(119, 119, 142, 0.05)",
        offsetX: 0,
        offsetY: 0,
      },
      axisTicks: {
        show: false,
        borderType: "solid",
        color: "rgba(119, 119, 142, 0.05)",
        width: 6,
        offsetX: 0,
        offsetY: 0,
      },
      labels: {
        rotate: -90,
      },
    },
    colors: [
      "rgba(142, 84, 233, 1)",
      "rgba(142, 84, 233, 0.6)",
      "rgba(142, 84, 233, 0.3)",
      "#ebeff5",
    ],
  };
  var chart2 = new ApexCharts(document.querySelector("#audienceReport"), options);
  chart2.render();
}
function audienceReport() {
  chart2.updateOptions({
    colors: [
      "rgb(" + myVarVal + ")",
      "rgba(" + myVarVal + ", 0.6)",
      "rgba(" + myVarVal + ", 0.3)",
      "#ebeff5",
    ],
  });
}

// Sessions By Device Chart
var element = document.getElementById("sessionsByDevice");
if (element !== null) {
  element.innerHTML = "";
  var options = {
    series: [
      {
        name: "Tablet",
        data: [[30, 25, 35, 50]],
      },
      {
        name: "Mobile",
        data: [[10, 35, 55, 60]],
      },
      {
        name: "Desktop",
        data: [[5, 5, 40, 50]],
      },
      {
        name: "Tabs",
        data: [[20, 40, 65, 60]],
      },
    ],
    chart: {
      height: 340,
      type: "bubble",
      toolbar: {
        show: false,
      },
    },
    grid: {
      borderColor: "#f3f3f3",
      strokeDashArray: 3,
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: true,
      fontSize: "13px",
      labels: {
        colors: "#959595",
      },
      markers: {
        width: 10,
        height: 10,
      },
    },
    xaxis: {
      min: 0,
      max: 40,
      labels: {
        show: false,
      },
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      max: 50,
      labels: {
        show: false,
      },
    },
    colors: ["#4876e6", "#4876e6", "#f6c364", "rgba(142, 84, 233, 1)"],
  };
  var chart1 = new ApexCharts(document.querySelector("#sessionsByDevice"), options);
  chart1.render();
}
function sessionsByDevice() {
  chart1.updateOptions({
    colors: ["#4876e6", "#4876e6", "#f6c364", "rgb(" + myVarVal + ")"],
  });
}

var element = document.getElementById("popTrades");
if (element !== null) {
  element.innerHTML = "";
  var options = {
    series: [73],
    chart: {
      type: "radialBar",
      offsetY: -20,
      offsetX: -5,
      height: 300,
      width: 250,
      foreColor: "#5d6162",
      fontFamily: "Poppins, Arial, sans-serif",
      sparkline: {
        enabled: true,
      },
    },
    colors: ["rgba(142, 84, 233, 0.99)", "rgba(0, 144, 172, 0.55)"],
    plotOptions: {
      radialBar: {
        startAngle: -90,
        endAngle: 90,
        track: {
          background: "#4876e6",
          strokeWidth: "97%",
        },
        dataLabels: {
          name: {
            show: false,
          },
          value: {
            offsetY: -3,
            fontSize: "15px",
            fontWeight: "500",
          },
        },
      },
    },
    fill: {
      type: "gradient",
      gradient: {
        shade: "light",
        shadeIntensity: 0.3,
        opacityFrom: 1,
        opacityTo: 1,
        stops: [0, 30, 50, 91],
      },
    },
    stroke: {
      dashArray: 3,
    },
  };
  var chart = new ApexCharts(document.querySelector("#popTrades"), options);
  chart.render();
}
function popTrades() {
  function rgbToHex(r, g, b) {
    return "#" + ((1 << 24) | (r << 16) | (g << 8) | b).toString(16).slice(1);
  }
  chart.updateOptions({
      colors: [
        rgbToHex(
          myVarVal.split(",")[0],
          myVarVal.split(",")[1],
          myVarVal.split(",")[2]
        ),
        "rgba(0, 144, 172, 0.55)",
      ],
  });
}
