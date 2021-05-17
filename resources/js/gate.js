export default class Gate {

	constructor(userType){
		this.userType = userType;
	}

	isAdmin(){
		return this.userType === 'admin'
	}

	isAuthor(){
		return this.userType === 'author'
	}

	isUser(){
		return this.userType === 'user'
	}

}