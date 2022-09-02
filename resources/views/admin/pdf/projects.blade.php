<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<div class="card">
<div class="card-header">
   Project list
   </div>

<div class="card-body">
    <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable datatable-Project">
            <thead>
                <tr>
                   
                    <th>
                       #ID
                    </th>
                    <th>
                       Project Name
                    </th>
                    <th>
                       Client
                    </th>
                    <th>
                      Description
                    </th>
                    <th>
                      Start Date
                    </th>
                    <th>
                        End Date
                    </th>
                    <th>
                      Budget
                    </th>
                    <th>
                      Status
                    </th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $key => $project)
                    <tr data-entry-id="{{ $project->id }}">
                      
                        <td style="margin-left: 3px;">
                            {{ $project->id ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                            {{ $project->name ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                            {{ $project->client->first_name ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                            {{ $project->description ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                          {{ date('j \\ F Y', strtotime($project->start_date)) }}
                        </td>
                        <td style="margin-left: 3px;">
                        {{ date('j \\ F Y', strtotime($project->end_date)) }} 
                        </td>
                        <td style="margin-left: 3px;">
                        @if($project->payment_base=='fixed')
                        ${{ $project->total_budget }}
                    
                    @endif
                    @if($project->payment_base=='hourly')
                       ${{ $project->per_hour_budget }}
                    
                    @endif
                        </td>
                        <td style="margin-left: 3px;">
                            {{ $project->status->name ?? '' }}
                        </td>
                        

                    </tr>
                @endforeach
            </tbody>
        </table>
      
    </div>
</div>
</div>
</body>
</html>