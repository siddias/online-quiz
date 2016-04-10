var limit;
function init()
{
 limit=Number(prompt("Enter number of buttons to be placed",""));
 var nod=(""+limit).length;
 if(Math.ceil(limit/2)>12)
	document.getElementById("scrollbar").style.height=37+"em";
 var pad = "";
 for(var i=1;i<=nod;i++)
	pad+="0";
 for(var i=1;i<=Math.ceil(limit/2);i++)
	addButtonElement("panel1", pad.substring(0, pad.length - (""+i).length) + i );
 for(var i=Math.ceil(limit/2)+1;i<=limit;i++)
	addButtonElement("panel2", pad.substring(0, pad.length - (""+i).length) + i );
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
  var ni = document.getElementsByClassName("ques");
  var i = this.id.substring( this.id.search(/\d/) );
  ni[0].innerHTML="Question Number "+Number(i)+"<br/>";
  ni[0].id="ques"+Number(i);
  if(this.className.search("btn btn-default") == 0 )
	document.getElementById("center").innerHTML="Flag";
  else
	document.getElementById("center").innerHTML="Unflag";

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
  var ni = document.getElementsByClassName("ques");
  var i = ni[0].id.substring( ni[0].id.search(/\d/) );
  if( (i=Number(i)+1) > limit)
	{
		alert("This is the Last Question!!");
		i--;
	}
  document.getElementById('button'+i).click();
  document.getElementById('button'+i).focus();
}

function prevQues()
{
  var ni = document.getElementsByClassName("ques");
  var i = ni[0].id.substring( ni[0].id.search(/\d/) );
  if( (i=Number(i)-1) < 1)
	{
		alert("This is the First Question!!");
		i++;
	}
  document.getElementById('button'+i).click();
  document.getElementById('button'+i).focus();
}


function markUnmarkQues()
{
  var ni = document.getElementsByClassName("ques");
  var i = ni[0].id.substring( ni[0].id.search(/\d/) );
  var b=document.getElementById("button"+Number(i));
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