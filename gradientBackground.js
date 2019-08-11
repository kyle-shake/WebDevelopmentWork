    var colors = new Array(
    [102,153,255], [102,179,255], [102,217,255], [102,255,255],[102,255,217],[102,255,179]
    );
    var colorIndices = [0, 1, 2, 3];
    var gradientSpeed = 0.002;
    var step = 0;

    function bgColorGradient(){
      var c0_0 = colors[colorIndices[0]];
      var c0_1 = colors[colorIndices[1]];
      var c1_0 = colors[colorIndices[2]];
      var c1_1 = colors[colorIndices[3]];
      var istep = 1 - step;

      var r1 =Math.round(istep * c0_0[0] + step * c0_1[0]);
      var g1 =Math.round(istep * c0_0[1] + step * c0_1[1])
      var b1 =Math.round(istep * c0_0[2] + step * c0_1[2])
      var color1 = "rgb("+r1+","+g1+","+b1+")";

      var r1 =Math.round(istep * c1_0[0] + step * c1_1[0]);
      var g1 =Math.round(istep * c1_0[1] + step * c1_1[1])
      var b1 =Math.round(istep * c1_0[2] + step * c1_1[2])
      var color2 = "rgb("+r1+","+g1+","+b1+")";


      document.getElementById("backgroundGradient").style.background="-webkit-gradient(linear, left top, right top, from("+color1+"), to("+color2+"))";
      document.getElementById("backgroundGradient").style.background="-webkit-linear-gradient("+color1+", "+color2+")";
      document.getElementById("backgroundGradient").style.background="-moz-linear-gradient(left, "+color1+" 0%, "+color2+" 100%)";
      document.getElementById("backgroundGradient").style.background="-ms-linear-gradient("+color1+", "+color2+")";
      document.getElementById("backgroundGradient").style.background="-o-linear-gradient("+color1+", "+color2+")";
      document.getElementById("backgroundGradient").style.background="linear-gradient("+color1+", "+color2+")";
      document.getElementById("backgroundGradient").style.filter="progid:DXImageTransform.Microsoft.Alpha(startColorstr='"+color1+"', endColorstr='"+color2+"')";

      step += gradientSpeed;
      if(step >= 1){
        step %= 1;
        colorIndices[0] = colorIndices[1];
        colorIndices[2] = colorIndices[3];

        colorIndices[1] = (colorIndices[1] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;
        colorIndices[3] = (colorIndices[3] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;
      }
    }

    setInterval(bgColorGradient, 10);
