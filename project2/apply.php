<?php
$pageTitle = 'Finova Technologies | Apply';
$mainLabel = 'Job application form';
include __DIR__ . '/header.inc';
include __DIR__ . '/nav.inc';
?>

        <section class ="heroApply">
            <div class = "heroContentApply">
                <h2>Apply to Finova Technologies Today</h2>
                <p>Join our team and help shape the future of financial technology.</p>
            </div>
        </section>

        <div class="applyForm">

            <form action="process_eoi.php" method="POST">
                <h1 style="text-align: center; background: #dbdcdc;" >Application Form</h1>
                <br>
                <fieldset>
                    <legend><strong>Job</strong></legend>
                    <p class="infoTitle"> <label for="jobReferenceNumber">Job Reference Number <span class="required" aria-hidden="true">*</span></label></p>
                    <div class="nameInputs">
                        <input type="text" name="jobReferenceNumber" id="jobReferenceNumber" pattern="[A-Za-z0-9]{6}" required="required" maxlength="6" placeholder="Job Reference Number">
                    </div>
                </fieldset>
                
                <fieldset>
                    <legend><strong>Personal Information</strong></legend>

                    <div class="Name">
                        <p class="infoTitle"> <label for="firstName">Name <span class="required" aria-hidden="true">*</span></label></p>
                        <div class="nameInputs">
                            <input type="text" name="firstName" id="firstName" pattern="[A-Za-z]{1,20}" required="required" maxlength="20" placeholder="First Name">
                            <input type="text" name="lastName" id="lastName" pattern="[A-Za-z]{1,20}" required="required" maxlength="20" placeholder="Last Name">
                        </div>
                    </div>

                    <div class="dateOfBirth">
                        <p class="infoTitle"> <label for="dateOfBirth">Date of Birth <span class="required" aria-hidden="true">*</span></label></p>
                        <div class="nameInputs">
                            <input type="text" name="dateOfBirth" id="dateOfBirth" required="required" placeholder="dd/mm/yyyy" pattern="(0[1-9]|[12][0-9]|3[0-1])/(0[1-9]|1[0-2])/[0-9]{4}">
                        </div>
                    </div>

                </fieldset>
                
                <fieldset>
                    <legend><strong>Gender <span class="required" aria-hidden="true">*</span></strong></legend>
                     <div class="nameInputs">
                            <input type="radio" name="Gender" id="Male" required="required" value="Male">
                            <label for="Male" style="font-size: 14px;">Male</label>

                            <input type="radio" name="Gender" id="Female" value="Female">
                            <label for="Female" style="font-size: 14px;">Female</label>

                            <input type="radio" name="Gender" id="Other" value="Other">
                            <label for="Other" style="font-size: 14px;">Other</label>
                        </div>
                </fieldset>

                <fieldset>
                    <legend><strong>Location Information</strong></legend>

                    <div class="Location">
                        <p class="infoTitle"> <label for="streetAddress">Address <span class="required" aria-hidden="true">*</span></label></p>
                        <div class="nameInputs">
                            <input type="text" name="streetAddress" id="streetAddress" pattern="[A-Za-z0-9\s]{1,40}" required="required" maxlength="40" placeholder="Street Address">
                            <input type="text" name="suburbTown" id="suburbTown" pattern="[A-Za-z0-9\s]{1,40}" required="required" maxlength="40" placeholder="Suburb/Town">
                        </div>

                         <div class="nameInputs">
                            <input type="text" name="Postcode" id="Postcode" pattern="[0-9]{4}" required="required" maxlength="4" placeholder="Postcode">
                        </div>

                        <div class="nameInputs">
                            <p class="infoTitle"> <label for="State">State <span class="required" aria-hidden="true">*</span></label></p>
                            <select name="State" id="State" aria-label="State of residence" required="required">
                                <option value="">Please Select</option>
                                <option value="VIC">VIC</option>
                                <option value="NSW">NSW</option>
                                <option value="QLD">QLD</option>
                                <option value="NT">NT</option>
                                <option value="WA">WA</option>
                                <option value="SA">SA</option>
                                <option value="TAS">TAS</option>
                                <option value="ACT">ACT</option>
                            </select>
                         </div>
                    </div>
                </fieldset>

                 <fieldset>
                    <legend><strong>Contact</strong></legend>

                      <div class="Email">
                        <p class="infoTitle"> <label for="email">Email Address <span class="required" aria-hidden="true">*</span></label></p>
                        <div class="nameInputs">
                            <input type="email" name="email" id="email" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*\.[A-Za-z]{2,}" title="Please enter a valid email address with a domain, e.g. name@example.com" required="required" placeholder="example@domain.com"> <!-- Email validation pattern provided by GenAI-->
                        </div>
                    </div>

                    <div class="Number">
                        <p class="infoTitle"> <label for="phone">Phone Number <span class="required" aria-hidden="true">*</span></label></p>
                        <div class="nameInputs">
                            <input type="text" name="phone" id="phone" pattern="[0-9]{8,12}" required="required" maxlength="12" placeholder="">
                        </div>
                    </div>

                </fieldset>

                  <fieldset>
                    <legend><strong>Relevant Skills</strong></legend>

                      <div class="skillList" role="group" aria-label="Relevant skills">
                        <p class="infoTitle"> <label>Select the skills that apply to you (at least one): <span class="required" aria-hidden="true">*</span></label></p>
                        <div class="nameInputs">

                            <input type="checkbox" id="Programming" name="skills[]" value="Programming">
                            <label for="Programming" class="relevantSkills">Programming</label>

                            <input type="checkbox" id="MicrosoftExcel" name="skills[]" value="Microsoft Excel">
                            <label for="MicrosoftExcel" class="relevantSkills">Understanding of Microsoft Excel</label>
                            
                            <input type="checkbox" id="Leadership" name="skills[]" value="Leadership">
                            <label for="Leadership" class="relevantSkills">Leadership</label>
                            
                            <input type="checkbox" id="Communication" name="skills[]" value="Communication">
                            <label for="Communication" class="relevantSkills">Communication</label>
                              
                        </div>
                        <div class="nameInputs">
                                <input type="checkbox" id="Organisation" name="skills[]" value="Organisation">
                                <label for="Organisation" class="relevantSkills">Organisation</label>

                                <input type="checkbox" id="ProblemSolving" name="skills[]" value="Problem Solving">
                                <label for="ProblemSolving" class="relevantSkills">Problem Solving</label>

                                <input type="checkbox" id="Teamwork" name="skills[]" value="Teamwork">
                                <label for="Teamwork" class="relevantSkills">Teamwork</label>

                                <input type="checkbox" id="TimeManagement" name="skills[]" value="Time Management">
                                <label for="TimeManagement" class="relevantSkills">Time Management</label>
                        </div>
                    </div>

                    <div class="otherSkills">
                        <p class="infoTitle"> <label for="otherSkills">Other Skills</label></p>
                        <div class="nameInputs">
                            <textarea id="otherSkills" name="otherSkills" rows="6" cols="40" placeholder="List your other skills"></textarea>
                        </div>
                    </div>

                </fieldset>

                <div class="Submission">
                    <input type="submit" value="Apply" style="text-align: center;">
                </div>
                
            </form>
            </div>

<?php
include __DIR__ . '/footer.inc';
?>