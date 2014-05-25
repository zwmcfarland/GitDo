<?php
    include("HeaderFooter.php");
    $access_token = $_SESSION['access_token'];
    incHeader("GitDo");
?>


<!-- Script --->
<script>
    $(document).on('ready', function() {
        var formData = {access_token: '<?php echo $access_token; ?>'};
        $.ajax({
            url : "https://api.github.com/user/repos",
            dataType: 'json',
            data : formData,
            success: function(data, textStatus, jqXHR)
            {
                for(var i = 0; i < data.length; i++)
                {  
                    $('#txtRepoName').append('<option value="' + data[i].name + '">' + data[i].name + '</option>');   
                }
            }
        });
    });
    $(document).on('ready',function() {
        var username = "";
        var formData = {access_token: '<?php echo $access_token; ?>'};
        $.ajax({
            url : "https://api.github.com/user",
            async: false,
            dataType: 'json',
            data : formData,
            success: function(data, textStatus, jqXHR)
            {
                   $('#userName').val(data.login);
            }
        });
    });
    $(document).on('click','#postToRepo', function() {
        var repoName = $('#txtRepoName').val();
        var issueName = $('#issueName').val();
        var body = $('#result').html();
        var userName = $('#userName').val();
        if(issueName === '')
        {
            $('#issueNameDiv').addClass('has-error');
        }
        else
        {
            $.ajax({
                url : "create_issue.php",
                type: 'POST',
                dataType: 'json',
                data : {
                    userName : userName,
                    repoName : repoName,
                    issueName: issueName,
                    body     : body 
                },
                success: function(data, textStatus, jqXHR)
                {
                   if(typeof data[31] === 'undefined')
                   {
                        alert("This repo does not support issue tracking.");
                   }
                   else
                   {
                        var text = JSON.parse("{" + data[31].replace(/(^,)|(,$)/g, "") + "}");
                        window.location = text.html_url;
                   }
                }
            });
        }
    });
    $(document).on('click','#addTask', function() {
        var element = $(this).parent('div').parent('div').parent('li');
        element.after('<li>' +
                                '<div class="form-group">' +
                                    '<label>Task Name</label>' +
                                    '<input type="text" class="form-control taskInputs"/>' +
                                '</div>' +
                                '<div class="form-group">' +
                                    '<label>Task Description</label>' +
                                    '<textarea class="form-control taskInputs" style="resize: none;" rows="3"></textarea>' +
                                    '<div align="right" style="margin-top:10px;display:inline-block;">' +
                                        '<button type="button" id="addTask" class="btn btn-info">Add Task</button> ' +
                                        '<button type="button" class="btn removeTask">Remove task</button>' +
                                    '</div>' +
                                    '<div style="margin-top:10px; display:inline-block; float:right;">' +
                                        '<button type="button" class="btn btn-info addSubTask">Add Subtask</button> ' +  
                                    '</div>' +
                                '</div>' +
                            '</li>');
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        updateMarkdown();
        element.next().find('input').focus();
    });
    $(document).on('click','.addSubTask', function() {
        if($(this).parent('div').parent('div').parent('li').find('ol').length)
        {
            $(this).parent('div').parent('div').parent('li').find('ol').append('<li>' +
                                '<div class="form-group">' +
                                    '<label>Task Name</label>' +
                                    '<input type="text" class="form-control taskInputs"/>' +
                                '</div>' +
                                '<div class="form-group">' +
                                    '<label>Task Description</label>' +
                                    '<textarea class="form-control taskInputs" style="resize: none;" rows="3"></textarea>' +
                                    '<div align="left" style="margin-top:10px;display:inline-block;">' +
                                        '<button type="button" id="addTask" class="btn btn-info">Add Task</button> ' +
                                        '<button type="button" class="btn removeTask">Remove task</button>' +
                                    '</div>' +
                                    '<div style="margin-top:10px; display:inline-block; float:right;">' +
                                        '<button type="button" class="btn btn-info addSubTask">Add Subtask</button> ' +  
                                    '</div>' +
                                '</div>' +
                            '</li>');
        }
        else
        {
            $(this).parent('div').parent('div').parent('li').append('<ol><li>' +
                                '<div class="form-group">' +
                                    '<label>Task Name</label>' +
                                    '<input type="text" class="form-control taskInputs"/>' +
                                '</div>' +
                                '<div class="form-group">' +
                                    '<label>Task Description</label>' +
                                    '<textarea class="form-control taskInputs" style="resize: none;" rows="3"></textarea> ' +
                                    '<div align="left" style="margin-top:10px;display:inline-block;">' +
                                        '<button type="button" id="addTask" class="btn btn-info">Add Task</button> ' +
                                        '<button type="button" class="btn removeTask">Remove task</button>' +
                                    '</div>' +
                                    '<div style="margin-top:10px; display:inline-block; float:right;">' +
                                        '<button type="button" class="btn btn-info addSubTask">Add Subtask</button>' +
                                    '</div>' +
                                '</div>' +
                            '</li></ol>');
        }
        $("html, body").animate({ scrollTop:  $(this).parent('div').parent('div').parent('li').position().top }, "slow");
        updateMarkdown();
        $(this).parent('div').parent('div').parent('li').children('ol').children('li').find('input').focus();
    });
    $(document).on('click', '.removeTask', function() {
        if($('#tasks li').length && $('#tasks li').length > 1) {
            $(this).parent('div').parent('div').parent('li').remove();
            updateMarkdown();
        }
    });
    $(document).on('change', '.taskInputs', function() {
        updateMarkdown();
    });
    function generateMarkdown(listElements, spaces)
    {
        var markDown = "";
        listElements.children('li').each(function() { 
            var taskName = $(this).find('input').val();
            var taskDesc = $(this).find('textarea').val();
            markDown += spaces + '- [ ] ' + taskName + '\n';
            if(taskDesc !== '')
            {
                markDown +=  spaces + '\t* ' + taskDesc + '\n';
            }
            if($(this).find('ol').length)
            {
                markDown += generateMarkdown($(this).children('ol'), spaces + '  ');
            }
        });
        return markDown;
    }
    function updateMarkdown()
    {
         $('#result').html('');
         var result = "## " + $('#issueName').val() + '\n';
         result += generateMarkdown($('#tasks'), '');
         result += '\nGenerated by [GitDo.es](http://www.GitDo.es)';
         $('#result').html(result);
    }
    $(document).on('click', '#btn_login', function() {
        window.location = 'https://github.com/login/oauth/authorize?client_id=7e84085f49b17f9068d2&redirect_uri=http://<?php echo $_SERVER['SERVER_NAME'];?>/access_token.php&scope=repo&state=asdfasdfasdf';
    });
    $(document).on('click', '#copy', function() {
        window.prompt("Copy to clipboard: Ctrl+C, Enter", $("#result").html());
    });
    function SelectAll(id)
    {
        if (document.selection) {
            var range = document.body.createTextRange();
            range.moveToElementText(document.getElementById(id));
            range.select();
        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(document.getElementById(id));
            window.getSelection().addRange(range);
        }
    }
    $(document).on('click', '#title', function() {
        window.location = 'ToDo.php';
    });
</script>
<!-- END: Script --->

<!-- Style -->
    <style>
        #title:hover {
            cursor: pointer;
        }
    </style>
<!-- END: Style -->

<div class="row">
    <div id='title' class="col-md-1">
        <h1><b>GitDo<font color="#5bc0de">es</font></b></h1>
        <!--<h4><b>GitDo<font color="#5cb85c">.es</font></b></h4>--->
    </div>
    <div class="col-md-1 col-md-offset-10" style="height:90px;text-align:center;bottom:0px;">
        <div <?php if(!$access_token) { echo 'style="display:none;'; }else{ echo 'style=bottom:0px;margin-top:22px;'; } ?>>
            <button type="button" id="btn_logout" class="btn" onclick="window.location = 'logerouter.php';">Logout</button>
        </div>
        <div <?php if($access_token) { echo 'style="display:none;'; }else{ echo 'style=bottom:0px;margin-top:22px;'; }?>>
            <button type="button" id="btn_login" class="btn btn-info">Login</button>
        </div>
    </div>
</div>


<div style="margin-left:2%; width: 45%;float: left;">
    <div class="form-group" <?php if($access_token == '') { echo 'style="display:none;'; }?>>
        <label for="txtRepoName" class="control-label">Select Repo</label>
        <select id="txtRepoName" class="form-control">
        </select>
    </div>
    <div class="form-group" id="issueNameDiv">
        <label for="issueName" class="control-label">Todo Name</label>
        <input type="text" class="form-control taskInputs" id="issueName">
    </div>
    <ol id="tasks">
        <li>
            <div class="form-group">
                <label>Task Name</label>
                <input type="text" class="form-control taskInputs"/>
            </div>
            <div class="form-group">
                <label>Task Description</label>
                <textarea class="form-control taskInputs" style="resize: none;" rows="3"></textarea>
                <div align="left" style="margin-top:10px; display:inline-block;">
                    <button type="button" id="addTask" class="btn btn-info">Add Task</button>
                    <button type="button" class="btn removeTask">Remove task</button>
                </div>
                <div style="margin-top:10px; display:inline-block; float:right;">
                    <button type="button" class="btn btn-info addSubTask">Add Subtask</button>
                </div>
            </div>
        </li>
    </ol>
</div>
<div style="width: 50%;height:2%; float:right;" id="resultsDiv" >
    <label>Markdown</label>
    <pre id="result" contenteditable="false" onclick="SelectAll('result');"></pre>
    <div align="center">
        <button type="button" id="postToRepo" class="btn btn-success" <?php if($access_token == '') { echo 'style="display:none;"'; }?>>Post To Repo</button>
        <button type="button" class="btn" id="copy">Copy Markup</button>
    </div>
</div>
<input type="hidden" id="userName"/>
<?php
    incFooter();
?>