<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>


# Photoz : A RESTful Photo Gallery App
A simple photo gallery app made with Laravel 5.8, implementing a REST API

# Updates
A <strong>Live Version</strong> of this application can be found <a href='https://photozbytrijeet.000webhostapp.com'>here<a>.


## Instructions to run locally
1. Set up <strong>.env</strong> file with database name, username and password.
2. Migrate Database using <strong>php artisan migrate:fresh</strong>.
3. Set up Personal Client for Laravel Passport. <strong>php artisan passport:install </strong>
4. Symlink Storage <strong>php artisan storage:link</strong>
5. Host the application on a virtual <a href='https://weblizar.com/blog/how-setup-virtual-host-for-laravel-xampp-wamp/'>host<a>.


# Database Standards

## User
1. <strong>id</strong>- autoincremented, primary key
2. <strong>username</strong> - string, candidate key
3. <strong>first_name</strong> - string
4. <strong>last_name</strong> - string
5. <strong>email</strong> - email, candidate key
6. <strong>gender</strong> - integer. <strong>1: Male, 2: Female, 3: Others</strong>
7. <strong>profile_picture</strong> - string (address to image in storage)

## Album
1. <strong>id</strong> - autoincremented, primary
2. <strong>user_id</strong> - foregin to User->id
3. <strong>album_name</strong> - string
4. <strong>album_description</strong> - mediumtext
5. <strong>cover_picture</strong> - string (address to image in storage)
6. <strong>privacy</strong> - integer. <strong>1: Public, 2: Link Accessible, 3: Private</strong>

## Photo
1. <strong>id</strong> - autoincremented, primary
2. <strong>album_id</strong> - foregin to Album->id
3. <strong>photo_description</strong> - mediumtext
4. <strong>photo</strong> - string (address to image in storage)
5. <strong>privacy</strong> - integer. <strong>1: Public, 2: Link Accessible, 3: Private</strong>

Each table contains timestamps for created_at and updated_at


# RESTful API

## User

1. Store - <strong> POST => app/api/users/ </strong>  <br>
    request contains first_name,last_name,username,email,password_gender as required and profile_picture.
    Creates a User if details are valid and returns a access token.
2. Update/{id} - <strong> PUT => app/api/users/{id} </strong>  <br>
    Bearer token passed with headers
    If logged in user has same {id}, updates the user with changes.
3. Show/{id} - <strong> GET => app/api/users/{id}</strong>  <br>
    Bearer token may or may not be passed with headers
    Returns the User details, and if same as logged in user, returns all albums associated with it.
    For other users, or guest, returns User details and only public albums.
4. Destroy{id} - <strong> DELETE => app/api/users/{id}</strong>  <br>
    Bearer token passed with headers
    If user exists and logged in user has permission, requests Albums/Delete API to delete all albums, Revoke all the users tokens, and then delete the User. 
5. Index - <strong>GET => app/api/users/</strong>  <br>
    Returns list of all users and corresponding details.

## Album

1. Store - <strong> POST => app/api/albums/ </strong>  <br>
    Bearer token passed with headers
    request contains album_name,privacy as required and cover_picture,album_description.
    Creates the album for a valid user.
2. Update/{id} - <strong> PUT => app/api/albums/{id} </strong>  <br>
    Bearer token passed with headers
    request contains album_name,privacy as required and cover_picture,album_description.
    Updates the album, if album exists and User has permission.
3. Show/{id} - <strong> GET => app/api/albums/{id} </strong>  <br>
    Bearer token may or may not be passed with headers
    Returns the Album if User owns the album, and returns all photos associated with it.
    For other users, or guest, returns album if public or link accessible and returns only public photos.
4. Destroy{id} - <strong> DELETE => app/api/albums/{id} </strong>  <br>
    Bearer token passed with headers
    If album exists and logged in user has permission, requests Photos/Delete API to delete all photos in the album and then delete the album. 


## Photo

1. Store - <strong> POST => app/api/photos/ </strong>  <br>
    Bearer token passed with headers
    request contains photo, privacy, album_id as required and photo_description.
    Uploads the photo to the album, if album exists and logged in User owns the album.
    If the album size is over 1000, the upload fails
2. Update/{id} - <strong> PUT => app/api/photos/{id} </strong>  <br>
    Bearer token passed with headers
    request contains privacy, album_id as required and photo, photo_description.
    Updates the photo to the album, if photo/album exists and logged in User owns the album
3. Show/{id} - <strong> GET => app/api/photos/{id} </strong>  <br>
    Bearer token may or may not be passed with headers
    Returns the photo if logged in user owns the photo, or if the photo has a privacy of public or link accessible
4. Destroy{id} - <strong> DELETE => app/api/photos/{id} </strong>  <br>
    Bearer token passed with headers
    If photo exists and logged in user has permission, delete file from storage and entry from database.
    
## Passport
1. Login - <strong>POST => app/api/login </strong>  <br>
    request contains username and password.
    If verified, generates a token and returns it.
2. Logout - <strong>GET => app/api/logout </strong>  <br>
    If user is authorized, revokes the login token and logs out.
