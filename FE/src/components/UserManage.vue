<template>
  <div>
    <input placeholder="添加新员工" type="text" v-model="newUser"/>
    <Button color="primary" :disabled="newUser==''">添加</Button>
  </div>
</template>

<script>
export default {
  data(){
    return{
      newUser: ''
    }
  }
}
</script>