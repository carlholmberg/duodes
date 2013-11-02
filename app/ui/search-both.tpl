<div class="container">
    <h1>{Search}</h1>
    
</div>
    <!-- cut:noresult -->
    <p class="well">
        <strong>Inga #what# matchade din sökning.</strong>
    </p>
    <!-- /cut:noresult -->

<div class="container">
    <table>
        <tr><th>{Titles}</th><th>{Users}</th></tr>
        <tr>
            <td class="col-md-6">
                <!-- cut:resultTitle -->
                <p class="well">
                    <span class="glyphicon glyphicon-book"></span> <a href="title/#id#" title="#title#">#title#</a> <em>#author# (#date#)</em>
                </p>
                <!-- /cut:resultTitle -->
                <!-- paste:resultTitle -->
            </td>
            <td>
                <!-- cut:resultUser -->
                <p class="well">
                    <span class="glyphicon glyphicon-user"></span> <a href="user/#id#" title="#lastname#, #firstname#">#lastname#, #firstname# (#class#)</a>
                </p>
                <!-- /cut:resultUser -->
                <!-- paste:resultUser -->
            </td>
        </tr>
    </table>
    
</div>