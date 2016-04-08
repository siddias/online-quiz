function init()
{
 var limit=Number(prompt("Enter number of buttons to be placed",""));
 if(limit>10)
	document.getElementById("scrollbar").style.height=40+"em";
 for(var i=1;i<=Math.ceil(limit/2);i++)
	addButtonElement("panel1",i);
 for(var i=Math.ceil(limit/2)+1;i<=limit;i++)
	addButtonElement("panel2",i);
}

function addButtonElement(panel, num)
{
  var ni = document.getElementById(panel);
  var newdiv = document.createElement('button');
  var divIdName = 'button'+num;
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
  var i= this.id.substring( this.id.search(/\d/) );
  ni.innerHTML="Question Number "+i+"<br/>"; 
  var newdiv = document.createElement('button');
  var divIdName = 'nextQues'+( Number(i) +1 );
  newdiv.setAttribute('id',divIdName);
  newdiv.onclick=nextQues;
  newdiv.innerHTML = 'Next Question'+ (Number(i) + 1 );
  ni.appendChild(newdiv);
}

function nextQues()
{
  var i= this.id.substring( this.id.search(/\d/) );
  document.getElementById('button'+i).click();
}


function markUnmark()
{
  var id=this.id.substring(0,this.id.length-6)
  var b=document.getElementById(id);
  if(b.className.search("btn btn-default") == 0 )
		{
		b.className="btn btn-warning";
		this.innerHTML="Click to unmark";
	}
  else
	{
		b.className="btn btn-default";
		this.innerHTML="Click to mark";
	}
}