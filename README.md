To start a virtual environment for a Django project
    Type: "python -m venv "virtual_environment_name""
	 
To activate the virtual environment
    Type: "source "virtual_environment_name"/bin/activate" 
    Note: You must activate the V.ENV. everytime you want to work on your project.

To setup a new project
    Go to the virtual environment folder (ex: myworld)
    django-admin startproject "project_name"

To run a project
    Go to the folder "project_name"
    Type "python manage.py runserver"

To create an app
    Go to the folder "project_name"
    Type "python manage.py startapp "app_name""
    
To add new app website
    Go to the folder "project_name"
    Type "python manage.py migrate"
    
To create a table in the database
    Go to "project_name"
    Type "python manage.py makemigrations "app_name""
    Type "python manage.py migrate"
    
To view the SQL commands that were executed
    Type "python manage.py sqlmigrate "app_name" "code_name_numbers (ex: 0001)""
    
To enter SQL commands
    Type "python manage.py shell"
    Type "from members.models import "Table_name" (ex: Member) "
    
To print SQL data
    Member.objects.all()
    
To add data
    Type "member1 = Member(firstname='Emil', lastname='Refsnes')"
    Type "member1.save()"
    or
    member_list = [member1, ...]
    for x in member_list:
        x.save()

To update data
    Type "x = Member.objects.all()[4]"
    Type "x."attribute" (ex: x.firstname) = "value""
    Type "x.save()"
    
To delete data
    Type "x = Member.objects.all()[5]"
    Type "x.delete()"
    
To add columns after a table is created
    Just go to models.py and add the columns
    Type "python manage.py makemigrations members"
    Type "python manage.py migrate"
