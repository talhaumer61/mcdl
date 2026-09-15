
var element = document.getElementById("filestore");
if (element !== null) {
  element.innerHTML = "";
  var options = {
    series: [70],
    chart: {
      type: "radialBar",
      offsetY: -20,
      sparkline: {
        enabled: true,
      },
    },
    plotOptions: {
      radialBar: {
        startAngle: -90,
        endAngle: 90,
        track: {
          background: "#e7e7e7",
          strokeWidth: "80%",
          margin: 5, // margin is in pixels
          dropShadow: {
            enabled: false,
            top: 2,
            left: 0,
            opacity: 1,
            blur: 1,
          },
        },
        dataLabels: {
          name: {
            show: false,
          },
          value: {
            offsetY: -2,
            fontSize: "22px",
          },
        },
      },
    },
    grid: {
      padding: {
        top: -10,
      },
    },
    labels: ["Average Results"],
  };
  var chart = new ApexCharts(document.querySelector("#filestore"), options);
  chart.render();
}
function filestore() {
  setTimeout(()=>{
    chart.updateOptions({
      colors: ["rgba(" + myVarVal + ", 0.95)", "rgba(" + myVarVal + ", 0.15)"],
    })
  }, 300);
}
