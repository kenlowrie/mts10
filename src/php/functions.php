<?php

include_once ('session.php');

class cDisplay {
    protected $columns;
	
    public function __construct(){
        $this->columns = Array(
            'title' => True,
            'rating' => True,
            'genre' => False,
            'format' => False,
            'loanee' => False
        );
    }
    
	public function showTitle(){    return $this->columns['title'];     }
	public function showRating(){   return $this->columns['rating'];    }
	public function showGenre(){    return $this->columns['genre'];     }
	public function showFormat(){   return $this->columns['format'];    }
	public function showLoanee(){   return $this->columns['loanee'];    }

	public function setShowTitle($value){   $this->columns['title'] = $value;     }
	public function setShowRating($value){  $this->columns['rating'] = $value;    }
	public function setShowGenre($value){   $this->columns['genre'] = $value;     }
	public function setShowFormat($value){  $this->columns['format'] = $value;    }
	public function setShowLoanee($value){  $this->columns['loanee'] = $value;    }

}

function get_display_columns_object()
{
    if( IsSet($_SESSION['columns_displayed']) )
    	return unserialize($_SESSION['columns_displayed']);

    // Should I just set it with a default object if not there? This shouldn't happen...    	
    return NULL;
}

function set_display_columns_object($display_columns)
{
    	$_SESSION['columns_displayed'] = serialize($display_columns);
}

if (!IsSet($_SESSION['columns_displayed'])){
    set_display_columns_object(new cDisplay);
}

function show_col($col)
{
    $cd = get_display_columns_object();
    
    switch($col)
    {
    case 'title':
        return $cd->showTitle(); 
        break;
    case 'format':
        return $cd->showFormat(); 
        break;
    case 'genre':
        return $cd->showGenre(); 
        break;
    case 'rating':
        return $cd->showRating(); 
        break;
    case 'loanee':
        return $cd->showLoanee(); 
        break;
     }
     return False;
}


?>