namespace ABALookup\Controller;

use
	Zend\View\Model\ViewModel,
	ABALookup\ABALookupController
;
class ScheduleController extends ABALookupController {
	public function indexAction() {
		return new ViewModel();
	}
}