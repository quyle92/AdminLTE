import router from './router'

export default class Helper {

	filterPermission( resource ){
		let permission = JSON.parse(window.permission);
        let filterPermission = permission.filter( e => {
           return e.resourceName.toLowerCase() === resource;
        });
        delete filterPermission[0].resourceName;

        return filterPermission[0];
	}

	isShow(){
		let resource = router.app._router.history.current.name;
		
		return this.filterPermission( resource ).read;
	}


}