<style type="text/Css">
.test1
{
    border: solid 1px #FF0000;
    background: #FFFFFF;
    border-collapse: collapse;
}
.w-100{
    width:100%;
    display: inline-block;
    padding: 10px;
}
.w-50{
    /* background-color: blue; */
    display: inline;
    width: 50%;
}
.w-20{
    /* background-color: blue; */
    display: inline;
    width: 10%;
}
.w-80{
    /* background-color: blue; */
    display: inline;
    width: 80%;
}
.seccion_infection{
    font-size: 15px;
}
.wrapper {
    width: 500px;
}			
.progress-bar {
    width: 100%;
    background-color: #e0e0e0;
    padding: 3px;
    border-radius: 3px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, .2);
}
.progress-bar-fill {
    display: block;
    height: 22px;
    background-color: #659cef;
    border-radius: 3px;    
    transition: width 500ms ease-in-out;
}
.text-section{
    text-align: left;
    margin-top: 20px;
    padding-left: 5px
    margin-bottom: 5px;
}
.separador{
    width: 100%;
    padding: 10px;
    border-bottom: 1px solid black;
}
.centrado{
    display: block;
    text-align: center;
}
</style>
<link rel="stylesheet" type="text/Css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.8.2/css/bulma.css">

<body class="margenes">

    <div class="w-100 centrado">
        <h1 class="title is-3">Simple report</h1>
    </div>
    <div class="w-100 text-section">
        <h4 class="title is-5">Infected domains</h4>
    </div>
    <div class="separador">
    </div>
    <table class="w-100 table is-striped">
        <thead>
            <tr>
                <th>Domain</th>
                <th>Name</th>
                <th>User</th>
                <th>Action taken</th>
                <th>Status</th>
                <th>Infections</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($domains as $domain)
                <tr>
                    <td>{{$domain->url}}</td>
                    <td>{{$domain->name}}</td>
                    <td>{{ empty($domain->user->name) ? 'No user' : $domain->user->name }}</td>
                    <td>{{ ( count($domain->actions_takens) != 0 )? $domain->actions_takens[0]->type  : 'None' }}</td>
                    <td>{{$domain->status}}</td>
                    <td>{{count($domain->infections)}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="w-100 text-section">
        <h4 class="title is-5">Type of infections</h4>
    </div>
    <div class="separador">
    </div>
    <div class="w-100 seccion_infection">
        <div class="w-50" style="float: left;">
            <div class="w-20" style="">
                Spam
            </div>
            <div class="w-80" style="float: right;">
                <div class="progress-bar">
                    <span class="progress-bar-fill" style="width: 70%;"></span>
                </div>
            </div>
        </div>
        <div class="w-50 " style="float: left;">
            <div class="w-20">
                Malware
            </div>
            <div class="w-80" style="float: right;">
                <div class="progress-bar">
                    <span class="progress-bar-fill" style="width: 70%;"></span>
                </div>
            </div>
        </div>
    </div>
</body>