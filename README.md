# XLSHelper
XLS Helper for CakePHP 2.x - Exports a Model data to Excel. Useful for backup purposes. 

Copy XLSHelper to app/View/Helpers/ and follow instructions below: 


## Controller/ModelController.php
```php
public $helpers = array('Xls');


public function export() {
		$data = $this->Model->find('all');
		$this->set('data', $data);
		$this->layout = null;
		$this->autoLayout = false;

	}


```
## View/Model/export.ctp
```php
//Header 
$line= $data[0]['Model'];
$this->XLS->addRow(array_keys($line));

//Content
foreach ($data as $d)
{
	$line= $d['Model']; 
	$this->XLS->addRow($line);
}

//Render File
$filename='output';
echo  $this->XLS->render($filename);
```
