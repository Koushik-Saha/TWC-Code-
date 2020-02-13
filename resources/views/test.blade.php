<form action="/test" method="post">
    @csrf
    <input type="text" name="name[0]" value="Name 1">
    <br>
    <input type="text" name="name[1]" value="Name 1">
    <br>
    <input type="text" name="name[2]" value="Name 1">
    <br>
    <input type="submit" value="Submit">
</form>
