# online-exam-conductor

I created this simple project for our university development team. Why this topic? why not? It touches many web design topics from user role and privilege managment to detail user exeperience and flawless interaction.

#### The system have three types of users Admins, Counductors and Candidates. 
<ul>
<li>The have admin have a global privilege which includes registering conductors and candidates,track exams in any status such as active, cancled, and completed exams,  terminate exams and so on.</li>
<li>The conductor have a privilege to conduct exams, track exams which he/she conducts, see exam previews and get the result of each candidates and the status and questions of the exam in PDF format.</li>
<li>The candidate can participate on exams only on which he/she is invited to, get his result after completing the exam and check his result and all question with their answer after he/she exam completes the exam</li>
</ul>

#### Some feature of the system
<ul>
<li>Secure the exam by 
<ul>
    <li>preventing the candidate to leave the exam page and dispell the candidate if he/she did so.</li> 
    <li>by digesting all questions if internet connection is lost and display all questions after the connection is back and by preventing the candidate to minimize the          browser and dispell the candidate if he/she did so.</li>
    <li>Prevent the candidate to open two instance of the exam by generating a token when he/she enrolls to the exam.</li>
 </ul>
 </li>
 <li>Make sure uninvited candidates don't take the exam by emailing exam code to the selected candidates only and require the candidates to insert the exam code before enrolling to the exam </li>
<li>Provide exam preview option for the conductor.</li>
<li>Generate a PDF containing a list of candidates participated on an exam with the exam status and detail </li>
<li>Generate a PDF containing candidates information containing the results of all the exams he/she tooks </li>
</ul>
