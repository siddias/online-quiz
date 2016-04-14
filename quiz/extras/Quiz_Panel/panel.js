var limit,current,prev;
function init()
{
 limit=Number(prompt("Enter number of buttons to be placed",""));
 document.getElementById("main").style.visibility="visible";
 document.getElementById("left").className="btn btn-primary disabled";
 var nod=(""+limit).length;
 if(Math.ceil(limit/2)>12)
	document.getElementById("scrollbar").style.height=37+"em";
 var pad = "";
 for(var i=1;i<=nod;i++)
	pad+="0";
 for(var i=1;i<=limit;i++)
	{
		if(i%2==1)
			addButtonElement("panel1", pad.substring(0, pad.length - (""+i).length) + i );
		else
			addButtonElement("panel2", pad.substring(0, pad.length - (""+i).length) + i );

	}
 if(limit%2==1)
	{
	var ni = document.getElementById("panel2");
	var newdiv = document.createElement('button');
	newdiv.setAttribute('id',"hidden");
	newdiv.setAttribute("type", "button");
	newdiv.setAttribute("class", "btn btn-default btn-lg");
	newdiv.setAttribute("style", "visibility:hidden");
	newdiv.innerHTML =""+pad;
	ni.appendChild(newdiv);
	}
 current=1;
 prev=1;
 document.getElementById('button1').click();
 document.getElementById('button1').focus();
}

function addButtonElement(panel, num)
{
  var ni = document.getElementById(panel);
  var newdiv = document.createElement('button');
  var divIdName = 'button'+Number(num);
  newdiv.setAttribute('id',divIdName);
  newdiv.setAttribute("type", "button");
  newdiv.setAttribute("class", "btn btn-default btn-lg");
  newdiv.onclick=quesDiv;
  newdiv.innerHTML = num+" ";
  ni.appendChild(newdiv);
  
/*  ni=document.getElementById(divIdName);
  newdiv = document.createElement('a');
  divIdName = 'button'+num+'anchor';
  newdiv.setAttribute('id',divIdName);
  newdiv.setAttribute('href','#');
  newdiv.onclick=markUnmark;
  newdiv.innerHTML = 'Click to mark';
  ni.appendChild(newdiv);
*/

/*var ni = document.getElementById('panel');
  var newdiv = document.createElement('div');
  var divIdName = 'button'+num+'div';
  newdiv.setAttribute('id',divIdName);
  divIdName = 'button'+num;
  newdiv.innerHTML = '<button  type=\"button\" class=\"btn btn-default\" id=\"'+divIdName+'\" >'+num+'<br/><a href=\"#\" onclick=\"markUnmark(\''+divIdName+'\')\">Click to mark</a></button>';
  ni.appendChild(newdiv);

  newdiv.innerHTML = num+'<br/><a href=\"#\" onclick=\"markUnmark(\''+divIdName+'\')\">Click to mark</a>';

*/

}

function quesDiv()
{
  var ni = document.getElementById("ques");
  var i = this.id.substring( this.id.search(/\d/) );
  current=Number(i);
  ni.innerHTML="Question Number "+current+"<br/>";
  if(current==1)
  {
	  document.getElementById("left").className="btn btn-primary disabled";
	  document.getElementById("right").innerHTML="Next Question";
	  document.getElementById("right").onclick=nextQues;
  }
  else if(current==limit)
  {
		document.getElementById("right").onclick=sendData;
		document.getElementById("right").innerHTML="Submit";
		document.getElementById("left").className="btn btn-primary";
  }
  else
  {
	document.getElementById("left").className="btn btn-primary";
 	document.getElementById("right").onclick=nextQues;
	document.getElementById("right").innerHTML="Next Question";
  }
  if(this.className.search("btn btn-default") == 0 )
	document.getElementById("center").innerHTML="Flag";
  else
	document.getElementById("center").innerHTML="Unflag";
  prev=current;
/*var newdiv = document.createElement('button');
  var divIdName = 'nextQues'+( Number(i) + 1 );
  newdiv.setAttribute('id',divIdName);
  newdiv.onclick=nextQues;
  newdiv.innerHTML = 'Next Question'+ (Number(i) + 1 );
  ni.appendChild(newdiv);
*/
/*var d = document.getElementById('myDiv');
  var olddiv = document.getElementById(divNum);
  d.removeChild(olddiv);
*/
}


function nextQues()
{
		document.getElementById("left").className="btn btn-primary";
  if( current == limit)
	{
		document.getElementById("right").onclick=sendData;
		document.getElementById("right").click();
	}
  
  else if( (current+=1) == limit)
	{
		document.getElementById("right").innerHTML="Submit";
	}
	else
	{
		document.getElementById("right").onclick=nextQues;
		document.getElementById("right").innerHTML="Next Question";
	}
  document.getElementById('button'+current).click();
  document.getElementById('button'+current).focus();
}

function prevQues()
{
  if( current == limit)
	{
		document.getElementById("right").onclick=nextQues;
		document.getElementById("right").innerHTML="Next Question";
	}
  if( (current-=1) == 1)
	{
		document.getElementById("left").className="btn btn-primary disabled";
	}
  document.getElementById('button'+current).click();
  document.getElementById('button'+current).focus();
}


function markUnmarkQues()
{
  var b=document.getElementById("button"+current);
  if(b.className.search("btn btn-default") == 0 )
	{
		b.className="btn btn-warning btn-lg";
		document.getElementById("center").innerHTML="Unflag";
	}
  else
	{
		b.className="btn btn-default btn-lg";
		b.focus();
		document.getElementById("center").innerHTML="Flag";
	}
}

function sendData()
{
	alert(this.id);
}
