<?php

namespace Osedea\LaravelRest\Traits;

trait CommandModel
{
    /*
         * The attributes that can be used for filtering through the API.
         *
         * @var array
         */
    public static $filterable = [];

    /**
     * This holds the maximum number of items per page fot this model.
     * @var integer
     */
    protected $perPageMax = 25;

    public function getPerPageMax()
    {
        return $this->perPage;
    }

    public function setPerPageMax($perPageMax)
    {
        $this->perPageMax = $perPageMax;
    }

    /**
     * This scope automatically sorts a query depending on the request.
     * A request looks like this:
     *      - Sort by [column ASC]:                     ?sort=column
     *      - Sort by [column DESC]:                    ?sort=-column
     *      - Sort by [column1 ASC] and [column2 DESC]: ?sort=column1,-column2
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithRequestSort($query)
    {
        if (($sorts = \Request::get('sort', false))) {
            // We list all sorting options
            $sorts = explode(',', $sorts);

            foreach ($sorts as $sort) {
                $order = 'asc';

                // If the first character is a dash (-), then the sort order is reversed
                if (substr($sort, 0, 1) === '-') {
                    $order = 'desc';

                    // We need to remove the dash from the sort because it is used in the orderBy method.
                    $sort = substr($sort, 1, strlen($sort));
                }

                $query->orderBy($sort, $order);
            }
        }

        return $query;
    }

    public function scopeWithRequestEmbed($query)
    {
        if (($embeds = \Request::get('embed', false))) {
            $embeds = explode(',', $embeds);

            $query->with($embeds);
        }

        return $query;
    }
}
