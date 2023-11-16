<?php

function ModelFilter($modelName)
{
	$class = '\\App\\'.$modelName;
	$model = (new $class)->newQuery();

	// Search for a model based on their email.
	if (request('email')) {
		$model->where('email', request('email'));
	}

	// Search for a model based on their company.
	if (request('company')) {
		$model->where('company', request('company'));
	}

	// Search for a model based on their city.
	if (request('city')) {
		$model->where('city', request('city'));
	}

	// Only return model-records who are assigned
	// to the given sales manager(s).
	if (request('managers')) {
		$model->whereHas('managers', function ($query) {
			$query->whereIn('managers.name', request('managers'));
		});
	}

	// Has an 'event' parameter been provided?
	if (request('event')) {

	// Only return model-records who have
	// been invited to the event.
		$model->whereHas('rsvp.event', function ($query) {
			$query->where('event.slug', request('event'));
		});

	// Only return model-records who have responded
	// to the invitation (with any type of
	// response).
		if (request('responded')) {
			$model->whereHas('rsvp', function ($query) {
				$query->whereNotNull('responded_at');
			});
		}

	// Only return model-records who have responded
	// to the invitation with a specific
	// response.
		if (request('response')) {
			$model->whereHas('rsvp', function ($query) {
				$query->where('response', 'I will be attending');
			});
		}
	}

	// Get the results and return them.
	return $model;
}