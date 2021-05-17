function htmlDecode(input){
      var e = document.createElement('textarea');
      e.innerHTML = input;
      // handle case of empty input
      return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
    }
function viewPermission ( permissionJson ){
      let result = [];
      for (let item of  permissionJson){
        result.push( Object.values(item) );
      }
      return  Object.fromEntries(result);
    }
