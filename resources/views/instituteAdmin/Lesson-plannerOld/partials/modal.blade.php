<div class="modal" id="lessonModal">
    <div class="modal-content">
        <div class="modal-header">
            <span>📝 New Daily Lesson Plan</span>
            <button class="close-btn" onclick="closeModal()">×</button> 
        </div>
        <div class="success-message" id="successMessage">✓ Lesson plan saved successfully!</div> 
        <form id="lessonForm" onsubmit="saveLessonPlan(event)"> 
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Class <span class="form-required">*</span></label>
                    <select id="formClass" required>
                        <option value="">Select</option>
                        <option value="KG/A">KG / A</option> 
                        <option value="Nursery/A">Nursery / A</option>
                        <option value="Grade 1/A">Grade 1 / A</option>
                        <option value="Grade 2/A">Grade 2 / A</option>
                        <option value="Grade 3/A">Grade 3 / A</option>
                        <option value="Grade 4/A">Grade 4 / A</option>
                        <option value="Grade 5/A">Grade 5 / A</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Section <span class="form-required">*</span></label>
                    <select id="formSection" required>
                        <option value="">Select</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Subject <span class="form-required">*</span></label>
                    <select id="formSubject" required>
                        <option value="">Select</option>
                        <option value="English">English</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="Science">Science</option>
                        <option value="Art">Art</option>
                        <option value="Social Studies">Social Studies</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date <span class="form-required">*</span></label>
                    <input type="date" id="formDate" required />
                </div>
                <div class="form-group">
                    <label class="form-label">Month <span class="form-required">*</span></label>
                    <select id="formMonth" required>
                        <option value="">Select Month</option>
                        <option value="January 2026">January 2026</option>
                        <option value="February 2026">February 2026</option>
                        <option value="March 2026">March 2026</option>
                        <option value="April 2026">April 2026</option>
                        <option value="May 2026">May 2026</option>
                        <option value="June 2026">June 2026</option>
                        <option value="July 2026">July 2026</option>
                        <option value="August 2026">August 2026</option>
                        <option value="September 2026">September 2026</option>
                        <option value="October 2026">October 2026</option>
                        <option value="November 2026">November 2026</option>
                        <option value="December 2026">December 2026</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Week <span class="form-required">*</span></label>
                    <select id="formWeek" required>
                        <option value="">Select Week</option>
                        <option value="Week 1">Week 1</option>
                        <option value="Week 2">Week 2</option>
                        <option value="Week 3">Week 3</option>
                        <option value="Week 4">Week 4</option>
                        <option value="Week 5">Week 5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Planned periods</label>
                    <input type="text" id="formPeriods" placeholder="e.g. 5" />
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Title (optional)</label>
                <input type="text" id="formTitle" placeholder="e.g. Introduction to Photosynthesis" />
            </div>

            <div class="form-group">
                <label class="form-label">Learning objectives <span class="form-required">*</span></label>
                <textarea id="formObjectives" required placeholder="What should students be able to do?"></textarea>
            </div>

            <!-- ===== TOPICS SECTION ===== -->
            <div class="form-group">
                <label class="form-label">Topics for this date <span class="form-required">*</span></label>
                <div style="display:flex; gap:10px; margin-bottom:10px; flex-wrap:wrap;">
                    <button type="button" class="btn btn-primary btn-small" onclick="addTopic()">+ Add Topic</button>
                    <button type="button" class="btn btn-secondary btn-small" onclick="addMultipleTopics()">+ Add 3 Topics</button>
                </div>
                <div id="topicContainer"></div>
            </div>

            <div class="form-group">
                <label class="form-label">Teaching methods</label>
                <div class="checkbox-group">
                    <div class="checkbox-item"><input type="checkbox" id="lecture" name="methods" value="Lecture" /><label for="lecture">Lecture</label></div>
                    <div class="checkbox-item"><input type="checkbox" id="activity" name="methods" value="Activity" /><label for="activity">Activity</label></div>
                    <div class="checkbox-item"><input type="checkbox" id="demonstration" name="methods" value="Demonstration" /><label for="demonstration">Demonstration</label></div>
                    <div class="checkbox-item"><input type="checkbox" id="discussion" name="methods" value="Discussion" /><label for="discussion">Discussion</label></div>
                    <div class="checkbox-item"><input type="checkbox" id="groupwork" name="methods" value="Group work" /><label for="groupwork">Group work</label></div>
                    <div class="checkbox-item"><input type="checkbox" id="audiovisual" name="methods" value="Audio-visual" /><label for="audiovisual">Audio-visual</label></div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Teaching aids</label>
                <div class="checkbox-group">
                    <div class="checkbox-item"><input type="checkbox" id="textbook" name="aids" value="Textbook" /><label for="textbook">Textbook</label></div>
                    <div class="checkbox-item"><input type="checkbox" id="projector" name="aids" value="Projector" /><label for="projector">Projector</label></div>
                    <div class="checkbox-item"><input type="checkbox" id="smartboard" name="aids" value="Smart board" /><label for="smartboard">Smart board</label></div>
                    <div class="checkbox-item"><input type="checkbox" id="charts" name="aids" value="Charts / Models" /><label for="charts">Charts / Models</label></div>
                    <div class="checkbox-item"><input type="checkbox" id="worksheets" name="aids" value="Worksheets" /><label for="worksheets">Worksheets</label></div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Resources required</label>
                <textarea id="formResources" placeholder="e.g. Black board, flashcards"></textarea>
            </div>

            <div class="action-buttons">
                <button type="submit" class="btn btn-secondary">💾 Save draft</button>
                <button type="button" class="btn btn-success" onclick="submitLessonPlan()">🚀 Save & submit for review</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function addMultipleTopics() {
        for (let i = 0; i < 3; i++) {
            addTopicRow();
        }
    }
</script>